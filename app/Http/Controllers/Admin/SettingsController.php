<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(private SettingsService $settings) {}

    public function index()
    {
        return view('admin.settings.index', [
            'llmProvider' => $this->settings->get('llm', 'provider', 'anthropic'),
            'llmModel' => $this->settings->get('llm', 'model', ''),
            'hasApiKey' => (bool) $this->settings->get('llm', 'api_key'),
            'hasGithubPat' => (bool) $this->settings->get('github', 'pat'),
            'githubUsername' => $this->settings->get('github', 'username', ''),
            'siteName' => $this->settings->get('site', 'name', 'LoggyShip'),
            'accentColor' => $this->settings->get('site', 'accent_color', '#6366f1'),
            'seoTitle' => $this->settings->get('site', 'seo_title', ''),
            'seoDescription' => $this->settings->get('site', 'seo_description', ''),
            'language' => $this->settings->get('general', 'language', 'de'),
            'autoPublish' => (bool) $this->settings->get('general', 'auto_publish', false),
            'excludePaths' => $this->settings->get('rules', 'exclude_paths', ''),
            'excludeLabels' => $this->settings->get('rules', 'exclude_labels', ''),
            'includeLabels' => $this->settings->get('rules', 'include_labels', ''),
        ]);
    }

    public function updateLLM(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:anthropic,gemini',
            'model' => 'required|string',
            'api_key' => 'nullable|string',
        ]);

        $this->settings->set('llm', 'provider', $request->provider);
        $this->settings->set('llm', 'model', $request->model);

        if ($request->filled('api_key')) {
            $this->settings->set('llm', 'api_key', $request->api_key, encrypted: true);
        }

        return back()->with('success', 'LLM settings updated.');
    }

    public function updateGitHub(Request $request)
    {
        if ($request->filled('github_pat')) {
            $this->settings->set('github', 'pat', $request->github_pat, encrypted: true);
        }

        return back()->with('success', 'GitHub settings updated.');
    }

    public function updateSite(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'accent_color' => 'required|string|max:7',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'language' => 'required|string|max:10',
        ]);

        $this->settings->set('site', 'name', $request->site_name);
        $this->settings->set('site', 'accent_color', $request->accent_color);
        $this->settings->set('site', 'seo_title', $request->seo_title);
        $this->settings->set('site', 'seo_description', $request->seo_description);
        $this->settings->set('general', 'language', $request->language);
        $this->settings->set('general', 'auto_publish', $request->boolean('auto_publish') ? '1' : '0');

        return back()->with('success', 'Site settings updated.');
    }

    public function updateRules(Request $request)
    {
        $this->settings->set('rules', 'exclude_paths', $request->exclude_paths);
        $this->settings->set('rules', 'exclude_labels', $request->exclude_labels);
        $this->settings->set('rules', 'include_labels', $request->include_labels);

        return back()->with('success', 'Rules updated.');
    }
}
