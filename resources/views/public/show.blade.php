<x-layouts.public :title="$post->seo_title ?: $post->title" :siteName="$siteName" :seoDescription="$seoDescription">
    <style>
        .back-link {
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .back-link:hover {
            color: #6366f1;
        }
        .back-arrow {
            display: inline-block;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .back-link:hover .back-arrow {
            transform: translateX(-3px);
        }
        .article-enter {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.05s forwards;
            opacity: 0;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .share-btn {
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .share-btn:hover {
            background-color: #f3f4f6;
            color: #374151;
        }
        .article-prose {
            font-size: 1.0625rem;
            line-height: 1.8;
            color: #374151;
        }
        .article-prose h2 { font-size: 1.375rem; font-weight: 700; color: #111827; margin-top: 2.25em; margin-bottom: 0.75em; letter-spacing: -0.01em; }
        .article-prose h3 { font-size: 1.125rem; font-weight: 600; color: #111827; margin-top: 1.75em; margin-bottom: 0.5em; }
        .article-prose p { margin-bottom: 1.25em; }
        .article-prose ul, .article-prose ol { margin-bottom: 1.25em; padding-left: 1.5em; }
        .article-prose li { margin-bottom: 0.375em; }
        .article-prose a { color: #6366f1; text-decoration: underline; text-decoration-color: rgba(99, 102, 241, 0.3); text-underline-offset: 2px; transition: text-decoration-color 0.2s; }
        .article-prose a:hover { text-decoration-color: #6366f1; }
        .article-prose code { font-size: 0.875em; background: #f3f4f6; padding: 0.125em 0.375em; border-radius: 0.25rem; }
        .article-prose pre { background: #1f2937; color: #e5e7eb; border-radius: 0.75rem; padding: 1.25em; overflow-x: auto; margin-bottom: 1.5em; font-size: 0.875rem; }
        .article-prose pre code { background: none; padding: 0; color: inherit; }
        .article-prose img { border-radius: 0.75rem; margin: 1.5em 0; }
        .article-prose blockquote { border-left: 3px solid #e5e7eb; padding-left: 1em; color: #6b7280; font-style: italic; margin-bottom: 1.25em; }

        .copy-feedback {
            display: none;
            animation: fadeInUp 0.3s ease forwards;
        }
        .copy-feedback.show {
            display: inline;
        }
    </style>

    {{-- Back navigation --}}
    <div class="mb-8">
        <a href="{{ route('public.changelog') }}" class="back-link inline-flex items-center gap-1.5 text-sm font-medium text-gray-400">
            <span class="back-arrow">&larr;</span> All Updates
        </a>
    </div>

    {{-- Article --}}
    <article class="article-enter">
        {{-- Header --}}
        <header class="mb-8 border-b border-gray-100 pb-8">
            <div class="flex items-center gap-3">
                <x-category-badge :category="$post->category" />
                <span class="text-sm text-gray-300">&middot;</span>
                <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-sm text-gray-400">
                    {{ $post->published_at->format('F j, Y') }}
                </time>
            </div>

            <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-[2.25rem] sm:leading-tight">{{ $post->title }}</h1>

            @if($post->teaser)
                <p class="mt-4 text-lg leading-relaxed text-gray-500">{{ $post->teaser }}</p>
            @endif
        </header>

        {{-- Body --}}
        <div class="article-prose">
            {!! $post->body_html !!}
        </div>

        {{-- Share --}}
        <div class="mt-12 border-t border-gray-100 pt-8">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-400">Share this update</p>
                <button onclick="copyLink()" id="copy-btn" class="share-btn inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3.5 py-2 text-sm font-medium text-gray-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.914 0a4.5 4.5 0 00-1.242-7.244l-4.5-4.5a4.5 4.5 0 00-6.364 6.364L4.34 8.374" />
                    </svg>
                    <span id="copy-text">Copy link</span>
                </button>
            </div>
        </div>
    </article>

    <script>
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                var textEl = document.getElementById('copy-text');
                textEl.textContent = 'Copied!';
                setTimeout(function() { textEl.textContent = 'Copy link'; }, 2000);
            });
        }
    </script>
</x-layouts.public>
