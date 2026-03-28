<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderByDesc('published_at')->paginate(20);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'teaser' => 'nullable|string|max:500',
            'body_markdown' => 'required|string',
            'category' => 'required|in:new,improved,fixed,performance,security',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);

        $slug = Str::slug($validated['title']);
        $baseSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        Post::create([
            'slug' => $slug,
            'title' => $validated['title'],
            'teaser' => $validated['teaser'],
            'body_markdown' => $validated['body_markdown'],
            'body_html' => Str::markdown($validated['body_markdown']),
            'category' => $validated['category'],
            'is_published' => true,
            'published_at' => now(),
            'seo_title' => $validated['seo_title'],
            'seo_description' => $validated['seo_description'],
        ]);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post published.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'teaser' => 'nullable|string|max:500',
            'body_markdown' => 'required|string',
            'category' => 'required|in:new,improved,fixed,performance,security',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);

        $validated['body_html'] = Str::markdown($validated['body_markdown']);

        $post->update($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated.');
    }

    public function unpublish(Post $post)
    {
        $post->update(['is_published' => false, 'published_at' => null]);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post unpublished.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted.');
    }
}
