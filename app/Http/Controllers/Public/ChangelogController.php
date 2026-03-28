<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    public function __construct(private SettingsService $settings) {}

    public function index(Request $request)
    {
        $category = $request->get('category');
        $search = $request->get('q');

        $posts = Post::where('is_published', true)
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($search, fn ($q) => $q->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('body_markdown', 'like', "%{$search}%");
            }))
            ->orderByDesc('published_at')
            ->paginate(15);

        return view('public.index', [
            'posts' => $posts,
            'category' => $category,
            'search' => $search,
            'siteName' => $this->settings->get('site', 'name', 'Product Updates'),
            'seoTitle' => $this->settings->get('site', 'seo_title', 'Updates'),
            'seoDescription' => $this->settings->get('site', 'seo_description', ''),
            'accentColor' => $this->settings->get('site', 'accent_color', '#6366f1'),
        ]);
    }

    public function show(Post $post)
    {
        if (! $post->is_published) {
            abort(404);
        }

        return view('public.show', [
            'post' => $post,
            'siteName' => $this->settings->get('site', 'name', 'Product Updates'),
            'seoDescription' => $post->seo_description ?: $post->teaser,
            'accentColor' => $this->settings->get('site', 'accent_color', '#6366f1'),
        ]);
    }

    public function feed()
    {
        $posts = Post::where('is_published', true)
            ->orderByDesc('published_at')
            ->limit(20)
            ->get();

        $siteName = $this->settings->get('site', 'name', 'Product Updates');

        return response()
            ->view('public.feed', compact('posts', 'siteName'))
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
