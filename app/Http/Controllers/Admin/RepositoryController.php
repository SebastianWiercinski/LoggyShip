<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repository;
use App\Services\GitHub\GitHubSyncService;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
    public function index()
    {
        $repositories = Repository::orderBy('full_name')->get();

        return view('admin.repositories.index', compact('repositories'));
    }

    public function toggleActive(Repository $repository)
    {
        $repository->update(['is_active' => ! $repository->is_active]);

        $status = $repository->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "{$repository->full_name} {$status}.");
    }

    public function sync(Repository $repository, GitHubSyncService $syncService)
    {
        try {
            $stats = $syncService->syncRepository($repository);

            return back()->with('success',
                "Synced {$repository->full_name}: {$stats['commits']} commits, {$stats['prs']} PRs, {$stats['releases']} releases."
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Sync failed: '.$e->getMessage());
        }
    }

    public function updateSettings(Request $request, Repository $repository)
    {
        $repository->update([
            'sync_commits' => $request->boolean('sync_commits'),
            'sync_prs' => $request->boolean('sync_prs'),
            'sync_releases' => $request->boolean('sync_releases'),
        ]);

        return back()->with('success', 'Sync settings updated.');
    }
}
