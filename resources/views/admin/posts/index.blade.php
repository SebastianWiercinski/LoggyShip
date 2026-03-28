<x-layouts.app title="Published Posts">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Published Posts</h1>
        <a href="{{ route('admin.posts.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">New Manual Post</a>
    </div>

    @if($posts->isEmpty())
        <div class="mt-8 rounded-xl border border-gray-200 bg-white p-8 text-center">
            <p class="text-gray-500">No published posts yet.</p>
        </div>
    @else
        <div class="mt-6 space-y-3">
            @foreach($posts as $post)
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <x-category-badge :category="$post->category" />
                                @if(!$post->is_published)
                                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Unpublished</span>
                                @endif
                            </div>
                            <h3 class="mt-1 font-medium text-gray-900">{{ $post->title }}</h3>
                            @if($post->published_at)
                                <p class="mt-1 text-xs text-gray-400">Published {{ $post->published_at->diffForHumans() }}</p>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Edit</a>
                            @if($post->is_published)
                                <form method="POST" action="{{ route('admin.posts.unpublish', $post) }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Unpublish</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $posts->links() }}</div>
    @endif
</x-layouts.app>
