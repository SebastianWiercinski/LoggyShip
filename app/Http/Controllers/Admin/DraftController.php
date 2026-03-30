<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrandVoice;
use App\Models\Draft;
use App\Models\Post;
use App\Services\AI\DraftGeneratorService;
use App\Services\AI\ReleaseNoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DraftController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');

        $drafts = Draft::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.drafts.index', compact('drafts', 'status'));
    }

    public function show(Draft $draft)
    {
        $draft->load('sourceItems', 'brandVoice');

        return view('admin.drafts.show', compact('draft'));
    }

    public function edit(Draft $draft)
    {
        return view('admin.drafts.edit', compact('draft'));
    }

    public function update(Request $request, Draft $draft)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'teaser' => 'nullable|string|max:500',
            'body_markdown' => 'required|string',
            'category' => 'required|in:new,improved,fixed,performance,security',
        ]);

        $validated['body_html'] = Str::markdown($validated['body_markdown']);

        $draft->update($validated);

        return redirect()->route('admin.drafts.show', $draft)
            ->with('success', 'Draft updated.');
    }

    public function publish(Draft $draft)
    {
        $slug = Str::slug($draft->title);
        $baseSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        Post::create([
            'draft_id' => $draft->id,
            'repository_id' => $draft->repository_id,
            'slug' => $slug,
            'title' => $draft->title,
            'teaser' => $draft->teaser,
            'body_markdown' => $draft->body_markdown,
            'body_html' => $draft->body_html ?: Str::markdown($draft->body_markdown),
            'category' => $draft->category,
            'is_release_note' => $draft->is_release_note,
            'version' => $draft->version,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $draft->update(['status' => 'published']);

        return redirect()->route('admin.drafts.index')
            ->with('success', "Published: {$draft->title}");
    }

    public function discard(Draft $draft)
    {
        $draft->update(['status' => 'discarded']);

        return redirect()->route('admin.drafts.index')
            ->with('success', 'Draft discarded.');
    }

    public function regenerate(Draft $draft, DraftGeneratorService $generator, ReleaseNoteService $releaseNoteService)
    {
        $voice = $draft->brandVoice ?? BrandVoice::where('is_active', true)->first();

        if (! $voice) {
            return back()->with('error', 'No brand voice configured.');
        }

        try {
            if ($draft->is_release_note) {
                $items = $draft->sourceItems;
                if ($items->isEmpty()) {
                    return back()->with('error', 'No source items linked to this draft.');
                }
                $newDraft = $releaseNoteService->generate($items, $voice, $draft->version ?? '1.0.0', $draft->repository_id);
                $draft->update([
                    'title' => $newDraft->title,
                    'teaser' => $newDraft->teaser,
                    'body_markdown' => $newDraft->body_markdown,
                    'body_html' => $newDraft->body_html,
                    'category' => $newDraft->category,
                    'confidence_score' => $newDraft->confidence_score,
                    'status' => 'draft',
                ]);
                $newDraft->delete();
            } else {
                $generator->regenerateDraft($draft, $voice);
            }

            return redirect()->route('admin.drafts.show', $draft)
                ->with('success', 'Draft regenerated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Regeneration failed: '.$e->getMessage());
        }
    }
}
