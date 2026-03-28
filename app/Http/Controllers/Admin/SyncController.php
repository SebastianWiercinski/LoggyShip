<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repository;
use App\Services\GitHub\GitHubSyncService;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function __construct(private GitHubSyncService $syncService) {}

    public function triggerSync(Repository $repository)
    {
        try {
            $stats = $this->syncService->syncRepository($repository);

            $message = "Synced {$repository->full_name}: "
                ."{$stats['commits']} commits, "
                ."{$stats['prs']} PRs, "
                ."{$stats['releases']} releases.";

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Sync failed: '.$e->getMessage());
        }
    }
}
