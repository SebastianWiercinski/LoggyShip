<x-layouts.public :title="$post->seo_title ?: $post->title" :siteName="$siteName" :seoDescription="$seoDescription">
    <div class="mb-4">
        <a href="{{ route('public.changelog') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; All Updates</a>
    </div>

    <article>
        <div class="flex items-center gap-3 text-sm">
            <x-category-badge :category="$post->category" />
            <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-gray-400">
                {{ $post->published_at->format('F j, Y') }}
            </time>
        </div>

        <h1 class="mt-3 text-3xl font-bold text-gray-900">{{ $post->title }}</h1>

        @if($post->teaser)
            <p class="mt-3 text-lg text-gray-600">{{ $post->teaser }}</p>
        @endif

        <div class="prose prose-gray mt-6 max-w-none">
            {!! $post->body_html !!}
        </div>
    </article>
</x-layouts.public>
