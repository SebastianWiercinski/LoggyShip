<x-layouts.public :title="$seoTitle ?: 'Updates'" :siteName="$siteName" :seoDescription="$seoDescription">
    {{-- Search & Filter --}}
    <div class="mb-8">
        <form method="GET" action="{{ route('public.changelog') }}" class="flex gap-3">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search updates..."
                class="flex-1 rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">Search</button>
        </form>

        <div class="mt-3 flex flex-wrap gap-2">
            <a href="{{ route('public.changelog') }}" class="rounded-full px-3 py-1 text-xs font-medium {{ !$category ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">All</a>
            @foreach(['new' => 'New', 'improved' => 'Improved', 'fixed' => 'Fixed', 'performance' => 'Performance', 'security' => 'Security'] as $cat => $label)
                <a href="{{ route('public.changelog', ['category' => $cat]) }}" class="rounded-full px-3 py-1 text-xs font-medium {{ $category === $cat ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    {{-- Posts --}}
    @if($posts->isEmpty())
        <div class="py-12 text-center">
            <p class="text-gray-400">No updates yet. Check back soon!</p>
        </div>
    @else
        <div class="space-y-10">
            @foreach($posts as $post)
                <article class="group">
                    <div class="flex items-center gap-3 text-sm">
                        <x-category-badge :category="$post->category" />
                        <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-gray-400">
                            {{ $post->published_at->format('M j, Y') }}
                        </time>
                    </div>

                    <h2 class="mt-2 text-xl font-semibold text-gray-900">
                        <a href="{{ route('public.changelog.show', $post) }}" class="hover:text-indigo-600 transition-colors">
                            {{ $post->title }}
                        </a>
                    </h2>

                    @if($post->teaser)
                        <p class="mt-2 text-gray-600">{{ $post->teaser }}</p>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('public.changelog.show', $post) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            Read more &rarr;
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    @endif
</x-layouts.public>
