<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\BrandVoice;
use App\Models\Repository;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class SetupController extends Controller
{
    private const TOTAL_STEPS = 10;

    public function __construct(private SettingsService $settings) {}

    // Step 1: Welcome
    public function welcome()
    {
        return view('setup.welcome', [
            'step' => 1,
            'totalSteps' => self::TOTAL_STEPS,
            'stepTitle' => 'Welcome',
        ]);
    }

    public function welcomeStore(Request $request)
    {
        return redirect()->route('setup.system-check');
    }

    // Step 2: System Check
    public function systemCheck()
    {
        $checks = $this->runSystemChecks();

        return view('setup.system-check', [
            'step' => 2,
            'totalSteps' => self::TOTAL_STEPS,
            'stepTitle' => 'System Check',
            'checks' => $checks,
            'allPassed' => collect($checks)->every(fn ($c) => $c['passed']),
        ]);
    }

    public function systemCheckStore(Request $request)
    {
        $checks = $this->runSystemChecks();
        if (! collect($checks)->every(fn ($c) => $c['passed'])) {
            return back()->withErrors(['system' => 'Please resolve all system requirements before continuing.']);
        }

        return redirect()->route('setup.database');
    }

    // Step 3: Database
    public function database()
    {
        return view('setup.database', [
            'step' => 3,
            'totalSteps' => self::TOTAL_STEPS,
            'stepTitle' => 'Database',
            'currentDriver' => config('database.default'),
        ]);
    }

    public function databaseStore(Request $request)
    {
        $request->validate([
            'driver' => 'required|in:sqlite,mysql',
        ]);

        if ($request->driver === 'mysql') {
            $request->validate([
                'host' => 'required',
                'port' => 'required|numeric',
                'database' => 'required',
                'username' => 'required',
                'password' => 'nullable',
            ]);

            $this->settings->set('database', 'driver', 'mysql');
            $this->settings->set('database', 'host', $request->host);
            $this->settings->set('database', 'port', $request->port);
            $this->settings->set('database', 'database', $request->database);
            $this->settings->set('database', 'username', $request->username);
            $this->settings->set('database', 'password', $request->password, encrypted: true);
        }

        // Ensure tables exist
        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (\Exception $e) {
            return back()->withErrors(['database' => 'Migration failed: '.$e->getMessage()]);
        }

        return redirect()->route('setup.admin');
    }

    // Step 4: Admin Account
    public function admin()
    {
        return view('setup.admin', [
            'step' => 4,
            'totalSteps' => self::TOTAL_STEPS,
            'stepTitle' => 'Admin Account',
        ]);
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => explode('@', $request->email)[0],
                'password' => Hash::make($request->password),
                'is_admin' => true,
            ]
        );

        return redirect()->route('setup.llm');
    }

    // Step 5: LLM Provider
    public function llm()
    {
        return view('setup.llm', [
            'step' => 5,
            'totalSteps' => self::TOTAL_STEPS,
            'stepTitle' => 'LLM Provider',
        ]);
    }

    public function llmStore(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:anthropic,gemini',
            'model' => 'required|string',
            'api_key' => 'required|string',
        ]);

        $this->settings->set('llm', 'provider', $request->provider);
        $this->settings->set('llm', 'model', $request->model);
        $this->settings->set('llm', 'api_key', $request->api_key, encrypted: true);

        return redirect()->route('setup.brand-voice');
    }

    // Step 6: Brand Voice
    public function brandVoice()
    {
        return view('setup.brand-voice', [
            'step' => 6,
            'totalSteps' => self::TOTAL_STEPS,
            'stepTitle' => 'Brand Voice',
        ]);
    }

    public function brandVoiceStore(Request $request)
    {
        $request->validate([
            'language' => 'required|string|max:10',
            'sample_text' => 'nullable|string|max:5000',
            'no_go_words' => 'nullable|string|max:1000',
        ]);

        $sampleTexts = $request->sample_text
            ? [trim($request->sample_text)]
            : [];

        BrandVoice::updateOrCreate(
            ['name' => 'Default'],
            [
                'sample_texts' => $sampleTexts,
                'language' => $request->language,
                'no_go_words' => $request->no_go_words,
                'is_active' => true,
            ]
        );

        $this->settings->set('general', 'language', $request->language);

        return redirect()->route('setup.github');
    }

    // Step 7: GitHub Connect
    public function github()
    {
        return view('setup.github', [
            'step' => 7,
            'totalSteps' => self::TOTAL_STEPS,
            'stepTitle' => 'GitHub Connect',
        ]);
    }

    public function githubStore(Request $request)
    {
        $request->validate([
            'github_pat' => 'required|string',
        ]);

        // Test the token
        try {
            $response = Http::withToken($request->github_pat)
                ->accept('application/vnd.github+json')
                ->get('https://api.github.com/user');

            if (! $response->successful()) {
                return back()->withErrors(['github_pat' => 'Invalid GitHub token. Please check and try again.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['github_pat' => 'Could not connect to GitHub: '.$e->getMessage()]);
        }

        $this->settings->set('github', 'pat', $request->github_pat, encrypted: true);
        $this->settings->set('github', 'username', $response->json('login'));

        return redirect()->route('setup.repository');
    }

    // Step 8: Repository Select
    public function repository()
    {
        $pat = $this->settings->get('github', 'pat');
        $repos = [];

        if ($pat) {
            try {
                $response = Http::withToken($pat)
                    ->accept('application/vnd.github+json')
                    ->get('https://api.github.com/user/repos', [
                        'sort' => 'updated',
                        'per_page' => 100,
                        'type' => 'all',
                    ]);

                if ($response->successful()) {
                    $repos = $response->json();
                }
            } catch (\Exception $e) {
                // Silently fail, show empty list
            }
        }

        return view('setup.repository', [
            'step' => 8,
            'totalSteps' => self::TOTAL_STEPS,
            'stepTitle' => 'Repository',
            'repos' => $repos,
        ]);
    }

    public function repositoryStore(Request $request)
    {
        $request->validate([
            'repository' => 'required|string',
        ]);

        $pat = $this->settings->get('github', 'pat');
        $repoFullName = $request->repository;

        try {
            $response = Http::withToken($pat)
                ->accept('application/vnd.github+json')
                ->get("https://api.github.com/repos/{$repoFullName}");

            if (! $response->successful()) {
                return back()->withErrors(['repository' => 'Could not find repository.']);
            }

            $repoData = $response->json();

            Repository::updateOrCreate(
                ['github_id' => $repoData['id']],
                [
                    'owner' => $repoData['owner']['login'],
                    'name' => $repoData['name'],
                    'full_name' => $repoData['full_name'],
                    'description' => $repoData['description'],
                    'default_branch' => $repoData['default_branch'],
                    'is_active' => true,
                ]
            );
        } catch (\Exception $e) {
            return back()->withErrors(['repository' => 'Error: '.$e->getMessage()]);
        }

        return redirect()->route('setup.public-site');
    }

    // Step 9: Public Site Settings
    public function publicSite()
    {
        return view('setup.public-site', [
            'step' => 9,
            'totalSteps' => self::TOTAL_STEPS,
            'stepTitle' => 'Public Site',
        ]);
    }

    public function publicSiteStore(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'accent_color' => 'required|string|max:7',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);

        $this->settings->set('site', 'name', $request->site_name);
        $this->settings->set('site', 'accent_color', $request->accent_color);
        $this->settings->set('site', 'seo_title', $request->seo_title);
        $this->settings->set('site', 'seo_description', $request->seo_description);

        return redirect()->route('setup.finish');
    }

    // Step 10: Finish
    public function finish()
    {
        $repo = Repository::where('is_active', true)->first();

        return view('setup.finish', [
            'step' => 10,
            'totalSteps' => self::TOTAL_STEPS,
            'stepTitle' => 'Finish',
            'repo' => $repo,
        ]);
    }

    public function finishStore(Request $request)
    {
        $this->settings->set('general', 'installed', '1');

        // Log in the first admin user
        $user = User::where('is_admin', true)->first();
        if ($user) {
            auth()->login($user);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'LoggyShip is installed and ready! You can now sync your repository.');
    }

    // Helpers

    private function runSystemChecks(): array
    {
        return [
            [
                'name' => 'PHP Version',
                'required' => '8.3+',
                'current' => PHP_VERSION,
                'passed' => version_compare(PHP_VERSION, '8.3.0', '>='),
            ],
            [
                'name' => 'cURL Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('curl') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('curl'),
            ],
            [
                'name' => 'mbstring Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('mbstring') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('mbstring'),
            ],
            [
                'name' => 'OpenSSL Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('openssl') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('openssl'),
            ],
            [
                'name' => 'PDO SQLite Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('pdo_sqlite') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('pdo_sqlite'),
            ],
            [
                'name' => 'JSON Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('json') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('json'),
            ],
            [
                'name' => 'Storage Writable',
                'required' => 'Writable',
                'current' => is_writable(storage_path()) ? 'Writable' : 'Not writable',
                'passed' => is_writable(storage_path()),
            ],
            [
                'name' => 'Database Directory Writable',
                'required' => 'Writable',
                'current' => is_writable(database_path()) ? 'Writable' : 'Not writable',
                'passed' => is_writable(database_path()),
            ],
        ];
    }
}
