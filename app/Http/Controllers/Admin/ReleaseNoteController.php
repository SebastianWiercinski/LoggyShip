<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrandVoice;
use App\Models\Repository;
use App\Services\AI\ReleaseNoteService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReleaseNoteController extends Controller
{
    public function create(ReleaseNoteService $service)
    {
        $repositories = Repository::where('is_active', true)->orderBy('full_name')->get();

        $defaultRepoId = $repositories->count() === 1 ? $repositories->first()->id : null;
        $sinceDate = $service->detectLastReleaseDate($defaultRepoId);
        $suggestedVersion = $service->suggestNextVersion($defaultRepoId);
        $itemCount = $sinceDate
            ? $service->countAvailableItems($sinceDate, now(), $defaultRepoId)
            : 0;

        return view('admin.release-notes.create', [
            'repositories' => $repositories,
            'sinceDate' => $sinceDate?->format('Y-m-d') ?? now()->subMonth()->format('Y-m-d'),
            'suggestedVersion' => $suggestedVersion,
            'itemCount' => $itemCount,
        ]);
    }

    public function generate(Request $request, ReleaseNoteService $service)
    {
        $validated = $request->validate([
            'repository_id' => 'nullable|exists:repositories,id',
            'since' => 'required|date',
            'until' => 'nullable|date|after_or_equal:since',
            'version' => 'required|string|max:50',
        ]);

        $voice = BrandVoice::where('is_active', true)->first();

        if (! $voice) {
            return back()->with('error', 'No active brand voice configured. Please set one up first.');
        }

        $since = Carbon::parse($validated['since'])->startOfDay();
        $until = ! empty($validated['until']) ? Carbon::parse($validated['until'])->endOfDay() : now();
        $repoId = $validated['repository_id'] ?? null;

        $items = $service->gatherSourceItems($since, $until, $repoId);

        if ($items->isEmpty()) {
            return back()->with('error', 'No user-facing source items found in the selected date range.');
        }

        try {
            $draft = $service->generate($items, $voice, $validated['version'], $repoId);

            return redirect()->route('admin.drafts.show', $draft)
                ->with('success', "Release notes v{$validated['version']} generated with {$items->count()} items.");
        } catch (\Exception $e) {
            return back()->with('error', 'Generation failed: '.$e->getMessage());
        }
    }
}
