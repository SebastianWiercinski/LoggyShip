<x-layouts.public :title="$post->seo_title ?: $post->title" :siteName="$siteName" :seoDescription="$seoDescription">
    <style>
        /* --- Back navigation --- */
        .back-link {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .back-link:hover {
            color: #4f46e5;
        }
        .back-arrow {
            display: inline-block;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .back-link:hover .back-arrow {
            transform: translateX(-4px);
        }
        .back-pill {
            border: 1px solid transparent;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .back-pill:hover {
            background-color: rgba(99, 102, 241, 0.05);
            border-color: rgba(99, 102, 241, 0.12);
        }

        /* --- Article entrance --- */
        .article-enter {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.08s forwards;
            opacity: 0;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- Article header --- */
        .article-header {
            position: relative;
        }
        .article-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e5e7eb 20%, #e5e7eb 80%, transparent);
        }

        /* --- Prose typography --- */
        .article-prose {
            font-size: 1.0625rem;
            line-height: 1.85;
            color: #374151;
            letter-spacing: -0.003em;
        }
        .article-prose h2 {
            font-size: 1.375rem;
            font-weight: 700;
            color: #111827;
            margin-top: 2.5em;
            margin-bottom: 0.75em;
            letter-spacing: -0.02em;
            line-height: 1.3;
        }
        .article-prose h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-top: 2em;
            margin-bottom: 0.5em;
            letter-spacing: -0.01em;
        }
        .article-prose p {
            margin-bottom: 1.5em;
        }
        .article-prose ul, .article-prose ol {
            margin-bottom: 1.5em;
            padding-left: 1.5em;
        }
        .article-prose li {
            margin-bottom: 0.5em;
        }
        .article-prose li::marker {
            color: #d1d5db;
        }
        .article-prose a {
            color: #6366f1;
            text-decoration: underline;
            text-decoration-color: rgba(99, 102, 241, 0.25);
            text-underline-offset: 3px;
            transition: text-decoration-color 0.2s ease;
        }
        .article-prose a:hover {
            text-decoration-color: #6366f1;
        }
        .article-prose code {
            font-size: 0.875em;
            background: #f3f4f6;
            padding: 0.2em 0.4em;
            border-radius: 0.375rem;
            font-weight: 450;
            border: 1px solid rgba(0, 0, 0, 0.04);
        }
        .article-prose pre {
            background: #1a1a2e;
            color: #e2e8f0;
            border-radius: 1rem;
            padding: 1.5em;
            overflow-x: auto;
            margin-bottom: 1.75em;
            font-size: 0.875rem;
            line-height: 1.7;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }
        .article-prose pre code {
            background: none;
            padding: 0;
            color: inherit;
            border: none;
            font-weight: 400;
        }
        .article-prose img {
            border-radius: 1rem;
            margin: 2em 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.04);
        }
        .article-prose blockquote {
            border-left: 3px solid #e5e7eb;
            padding-left: 1.25em;
            color: #6b7280;
            font-style: italic;
            margin-bottom: 1.5em;
        }
        .article-prose hr {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e5e7eb 20%, #e5e7eb 80%, transparent);
            margin: 2.5em 0;
        }

        /* --- Share section --- */
        .share-btn {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .share-btn:hover {
            background-color: #f9fafb;
            border-color: #d1d5db;
            color: #374151;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        .share-btn:active {
            transform: translateY(0);
        }

        .share-divider {
            background: linear-gradient(90deg, transparent, #e5e7eb 20%, #e5e7eb 80%, transparent);
            height: 1px;
        }

        .copy-feedback {
            display: none;
            animation: fadeInUp 0.3s ease forwards;
        }
        .copy-feedback.show {
            display: inline;
        }

        /* --- Copied state --- */
        .share-btn-copied {
            background-color: #f0fdf4 !important;
            border-color: #86efac !important;
            color: #16a34a !important;
        }
    </style>

    {{-- Back navigation --}}
    <div class="mb-10">
        <a href="{{ route('public.changelog') }}" class="back-link back-pill inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium text-gray-400">
            <span class="back-arrow">&larr;</span> All Updates
        </a>
    </div>

    {{-- Article --}}
    <article class="article-enter">
        {{-- Header --}}
        <header class="article-header mb-10 pb-10">
            <div class="flex items-center gap-3">
                <x-category-badge :category="$post->category" />
                <span class="text-sm text-gray-200">&middot;</span>
                <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-sm font-medium text-gray-400">
                    {{ $post->published_at->format('F j, Y') }}
                </time>
            </div>

            <h1 class="mt-5 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-[2.5rem] sm:leading-[1.15]">{{ $post->title }}</h1>

            @if($post->teaser)
                <p class="mt-5 text-lg leading-relaxed text-gray-400">{{ $post->teaser }}</p>
            @endif
        </header>

        {{-- Body --}}
        <div class="article-prose">
            {!! $post->body_html !!}
        </div>

        {{-- Share --}}
        <div class="mt-16">
            <div class="share-divider"></div>
            <div class="flex items-center justify-between py-8">
                <p class="text-sm font-medium text-gray-300">Share this update</p>
                <button onclick="copyLink()" id="copy-btn" class="share-btn inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-400">
                    <svg id="copy-icon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                var btn = document.getElementById('copy-btn');
                var textEl = document.getElementById('copy-text');
                var iconEl = document.getElementById('copy-icon');
                textEl.textContent = 'Copied!';
                btn.classList.add('share-btn-copied');
                iconEl.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />';
                setTimeout(function() {
                    textEl.textContent = 'Copy link';
                    btn.classList.remove('share-btn-copied');
                    iconEl.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.914 0a4.5 4.5 0 00-1.242-7.244l-4.5-4.5a4.5 4.5 0 00-6.364 6.364L4.34 8.374" />';
                }, 2000);
            });
        }
    </script>
</x-layouts.public>
