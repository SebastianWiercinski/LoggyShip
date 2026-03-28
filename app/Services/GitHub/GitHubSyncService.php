<?php

namespace App\Services\GitHub;

use App\Models\Repository;
use App\Models\SourceItem;
use Illuminate\Support\Carbon;

class GitHubSyncService
{
    public function __construct(private GitHubClient $client) {}

    public function syncRepository(Repository $repo): array
    {
        $stats = ['commits' => 0, 'prs' => 0, 'releases' => 0];

        $since = $repo->last_synced_at?->toIso8601String();

        if ($repo->sync_prs) {
            $stats['prs'] = $this->syncPullRequests($repo, $since);
        }

        if ($repo->sync_commits) {
            $stats['commits'] = $this->syncCommits($repo, $since);
        }

        if ($repo->sync_releases) {
            $stats['releases'] = $this->syncReleases($repo);
        }

        $repo->update(['last_synced_at' => now()]);

        return $stats;
    }

    private function syncCommits(Repository $repo, ?string $since): int
    {
        $commits = $this->client->getCommits($repo->owner, $repo->name, $since);
        $count = 0;

        foreach ($commits as $commit) {
            $sha = $commit['sha'];
            $message = $commit['commit']['message'] ?? '';
            $title = strtok($message, "\n");

            // Skip merge commits
            if (str_starts_with($title, 'Merge ')) {
                continue;
            }

            $created = SourceItem::updateOrCreate(
                [
                    'repository_id' => $repo->id,
                    'type' => 'commit',
                    'github_id' => $sha,
                ],
                [
                    'title' => mb_substr($title, 0, 500),
                    'body' => mb_substr($message, 0, 5000),
                    'author' => $commit['commit']['author']['name'] ?? $commit['author']['login'] ?? 'unknown',
                    'url' => $commit['html_url'] ?? null,
                    'metadata' => [
                        'sha' => $sha,
                        'parents' => count($commit['parents'] ?? []),
                    ],
                    'created_on_github_at' => Carbon::parse($commit['commit']['author']['date']),
                ]
            );

            if ($created->wasRecentlyCreated) {
                $count++;
            }
        }

        return $count;
    }

    private function syncPullRequests(Repository $repo, ?string $since): int
    {
        $prs = $this->client->getPullRequests($repo->owner, $repo->name, 'closed', $since);
        $count = 0;

        foreach ($prs as $pr) {
            // Fetch changed files for context
            $files = $this->client->getPullRequestFiles($repo->owner, $repo->name, $pr['number']);
            $changedFiles = array_map(fn ($f) => $f['filename'], $files);

            $created = SourceItem::updateOrCreate(
                [
                    'repository_id' => $repo->id,
                    'type' => 'pull_request',
                    'github_id' => (string) $pr['number'],
                ],
                [
                    'title' => mb_substr($pr['title'], 0, 500),
                    'body' => mb_substr($pr['body'] ?? '', 0, 10000),
                    'author' => $pr['user']['login'] ?? 'unknown',
                    'url' => $pr['html_url'] ?? null,
                    'metadata' => [
                        'number' => $pr['number'],
                        'labels' => array_map(fn ($l) => $l['name'], $pr['labels'] ?? []),
                        'changed_files' => $changedFiles,
                        'additions' => $pr['additions'] ?? 0,
                        'deletions' => $pr['deletions'] ?? 0,
                        'merged_at' => $pr['merged_at'],
                        'base_branch' => $pr['base']['ref'] ?? null,
                        'head_branch' => $pr['head']['ref'] ?? null,
                    ],
                    'created_on_github_at' => Carbon::parse($pr['merged_at']),
                ]
            );

            if ($created->wasRecentlyCreated) {
                $count++;
            }
        }

        return $count;
    }

    private function syncReleases(Repository $repo): int
    {
        $releases = $this->client->getReleases($repo->owner, $repo->name);
        $count = 0;

        foreach ($releases as $release) {
            if ($release['draft'] ?? false) {
                continue;
            }

            $created = SourceItem::updateOrCreate(
                [
                    'repository_id' => $repo->id,
                    'type' => 'release',
                    'github_id' => (string) $release['id'],
                ],
                [
                    'title' => mb_substr($release['name'] ?? $release['tag_name'], 0, 500),
                    'body' => mb_substr($release['body'] ?? '', 0, 10000),
                    'author' => $release['author']['login'] ?? 'unknown',
                    'url' => $release['html_url'] ?? null,
                    'metadata' => [
                        'tag_name' => $release['tag_name'],
                        'prerelease' => $release['prerelease'] ?? false,
                    ],
                    'created_on_github_at' => Carbon::parse($release['published_at'] ?? $release['created_at']),
                ]
            );

            if ($created->wasRecentlyCreated) {
                $count++;
            }
        }

        return $count;
    }
}
