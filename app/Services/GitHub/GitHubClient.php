<?php

namespace App\Services\GitHub;

use App\Services\SettingsService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GitHubClient
{
    private ?string $token = null;

    public function __construct(private SettingsService $settings) {}

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function listRepositories(int $perPage = 100): array
    {
        $repos = [];
        $page = 1;

        do {
            $response = $this->request()->get('user/repos', [
                'sort' => 'updated',
                'per_page' => $perPage,
                'page' => $page,
                'type' => 'all',
            ]);

            if (! $response->successful()) {
                break;
            }

            $data = $response->json();
            if (empty($data)) {
                break;
            }

            $repos = array_merge($repos, $data);
            $page++;
        } while (count($data) === $perPage && $page <= 5);

        return $repos;
    }

    public function getCommits(string $owner, string $repo, ?string $since = null, int $perPage = 100): array
    {
        $params = ['per_page' => $perPage];
        if ($since) {
            $params['since'] = $since;
        }

        return $this->paginatedGet("repos/{$owner}/{$repo}/commits", $params);
    }

    public function getPullRequests(string $owner, string $repo, string $state = 'closed', ?string $since = null, int $perPage = 100): array
    {
        $params = [
            'state' => $state,
            'sort' => 'updated',
            'direction' => 'desc',
            'per_page' => $perPage,
        ];

        $prs = $this->paginatedGet("repos/{$owner}/{$repo}/pulls", $params);

        // Filter to only merged PRs and optionally by date
        return array_filter($prs, function ($pr) use ($since) {
            if (empty($pr['merged_at'])) {
                return false;
            }
            if ($since && $pr['merged_at'] < $since) {
                return false;
            }

            return true;
        });
    }

    public function getPullRequestFiles(string $owner, string $repo, int $number): array
    {
        return $this->paginatedGet("repos/{$owner}/{$repo}/pulls/{$number}/files", ['per_page' => 100]);
    }

    public function getReleases(string $owner, string $repo, int $perPage = 30): array
    {
        $response = $this->request()->get("repos/{$owner}/{$repo}/releases", [
            'per_page' => $perPage,
        ]);

        return $response->successful() ? $response->json() : [];
    }

    public function getUser(): ?array
    {
        $response = $this->request()->get('user');

        return $response->successful() ? $response->json() : null;
    }

    private function paginatedGet(string $url, array $params, int $maxPages = 5): array
    {
        $results = [];
        $page = 1;

        do {
            $params['page'] = $page;
            $response = $this->request()->get($url, $params);

            if (! $response->successful()) {
                break;
            }

            $data = $response->json();
            if (empty($data)) {
                break;
            }

            $results = array_merge($results, $data);
            $page++;
        } while (count($data) === ($params['per_page'] ?? 30) && $page <= $maxPages);

        return $results;
    }

    private function request(): PendingRequest
    {
        $token = $this->token ?? $this->settings->get('github', 'pat');

        return Http::baseUrl('https://api.github.com')
            ->withToken($token)
            ->accept('application/vnd.github+json')
            ->withHeaders(['X-GitHub-Api-Version' => '2022-11-28'])
            ->timeout(30);
    }
}
