<?php

namespace App\Console\Commands;

use App\Models\Repository;
use App\Services\GitHub\GitHubSyncService;
use Illuminate\Console\Command;

class SyncRepositories extends Command
{
    protected $signature = 'loggyship:sync {--repo= : Specific repository ID to sync}';

    protected $description = 'Sync active repositories from GitHub';

    public function handle(GitHubSyncService $syncService): int
    {
        $query = Repository::where('is_active', true);

        if ($repoId = $this->option('repo')) {
            $query->where('id', $repoId);
        }

        $repos = $query->get();

        if ($repos->isEmpty()) {
            $this->warn('No active repositories found.');

            return self::SUCCESS;
        }

        foreach ($repos as $repo) {
            $this->info("Syncing {$repo->full_name}...");

            try {
                $stats = $syncService->syncRepository($repo);
                $this->info("  Commits: {$stats['commits']}, PRs: {$stats['prs']}, Releases: {$stats['releases']}");
            } catch (\Exception $e) {
                $this->error("  Error: {$e->getMessage()}");
            }
        }

        $this->info('Sync complete.');

        return self::SUCCESS;
    }
}
