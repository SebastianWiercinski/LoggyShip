<x-layouts.public :title="$seoTitle ?: 'Updates'" :siteName="$siteName" :seoDescription="$seoDescription">
    <style>
        /* --- Staggered entrance --- */
        .hero-section {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .search-wrapper {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.06s forwards;
            opacity: 0;
        }
        .filter-pills {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.12s forwards;
            opacity: 0;
        }
        .post-list {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.18s forwards;
            opacity: 0;
        }

        /* --- Search --- */
        .search-input {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .search-input:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.08), 0 1px 3px rgba(0, 0, 0, 0.04);
            border-color: #818cf8;
            background-color: #fff;
        }
        .search-icon {
            transition: color 0.2s ease;
        }
        .search-input:focus ~ .search-icon-wrap .search-icon,
        .search-wrapper:focus-within .search-icon {
            color: #6366f1;
        }

        /* --- Filter Pills --- */
        .filter-pill {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid transparent;
        }
        .filter-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }
        .filter-pill-active {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .filter-pill-inactive {
            border-color: rgba(0, 0, 0, 0.04);
        }
        .filter-pill-inactive:hover {
            background-color: #f3f4f6;
            border-color: rgba(0, 0, 0, 0.06);
        }

        /* --- Post Card --- */
        .post-card {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border-left-width: 3px;
            border-left-style: solid;
        }
        .post-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.06), 0 2px 8px rgba(0, 0, 0, 0.03);
            background-color: #fff;
        }

        /* --- Read more arrow --- */
        .read-more-arrow {
            display: inline-block;
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .group:hover .read-more-arrow {
            transform: translateX(5px);
        }
        .read-more-link {
            transition: color 0.2s ease;
        }
        .group:hover .read-more-link {
            color: #4f46e5;
        }

        /* --- Timeline --- */
        .timeline-line {
            position: absolute;
            left: 5px;
            top: 28px;
            bottom: -28px;
            width: 1.5px;
            background: linear-gradient(to bottom, #d1d5db 0%, #e5e7eb 60%, #f3f4f6 100%);
        }
        .timeline-dot {
            position: absolute;
            left: 0;
            top: 6px;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            border: 2px solid #d1d5db;
            background: #fafafa;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 1;
        }
        .group:hover .timeline-dot {
            border-color: #6366f1;
            background: #eef2ff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.08);
            transform: scale(1.15);
        }

        /* --- Category borders --- */
        .category-border-new { border-left-color: #10b981; }
        .category-border-improved { border-left-color: #3b82f6; }
        .category-border-fixed { border-left-color: #f97316; }
        .category-border-performance { border-left-color: #a855f7; }
        .category-border-security { border-left-color: #ef4444; }

        /* --- Pagination --- */
        .pagination-wrapper nav > div:first-child { display: none; }
        .pagination-wrapper nav > div:last-child span,
        .pagination-wrapper nav > div:last-child a {
            border-radius: 9999px !important;
            min-width: 2.25rem;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .pagination-wrapper nav > div:last-child a:hover {
            background-color: #f3f4f6;
            transform: translateY(-1px);
        }

        /* --- Empty state --- */
        .empty-state {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.18s forwards;
            opacity: 0;
        }
        .empty-icon-ring {
            animation: pulse-ring 3s ease-in-out infinite;
        }
        @keyframes pulse-ring {
            0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.05); }
            50% { box-shadow: 0 0 0 12px rgba(99, 102, 241, 0); }
        }

        /* --- Post entrance stagger --- */
        .post-item { opacity: 0; animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .post-item:nth-child(1) { animation-delay: 0.2s; }
        .post-item:nth-child(2) { animation-delay: 0.28s; }
        .post-item:nth-child(3) { animation-delay: 0.36s; }
        .post-item:nth-child(4) { animation-delay: 0.44s; }
        .post-item:nth-child(5) { animation-delay: 0.52s; }
        .post-item:nth-child(6) { animation-delay: 0.6s; }
        .post-item:nth-child(7) { animation-delay: 0.68s; }
        .post-item:nth-child(8) { animation-delay: 0.76s; }
        .post-item:nth-child(9) { animation-delay: 0.84s; }
        .post-item:nth-child(10) { animation-delay: 0.92s; }
    </style>

    {{-- Hero --}}
    <div class="hero-section mb-12 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">{{ $siteName ?? 'Product Updates' }}</h1>
        <p class="mx-auto mt-4 max-w-md text-lg leading-relaxed text-gray-400">The latest features, improvements, and fixes.</p>
    </div>

    {{-- Search --}}
    <div class="search-wrapper mb-8">
        <form method="GET" action="{{ route('public.changelog') }}" class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                <svg class="search-icon h-[18px] w-[18px] text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8" />
                    <path stroke-linecap="round" d="M21 21l-4.35-4.35" />
                </svg>
            </div>
            <input type="text" name="q" value="{{ $search }}" placeholder="Search updates..."
                class="search-input block w-full rounded-2xl border border-gray-200/80 bg-white py-3.5 pl-12 pr-4 text-sm text-gray-900 placeholder-gray-300 shadow-sm focus:outline-none">
        </form>
    </div>

    {{-- Filter Pills --}}
    <div class="filter-pills mb-12 flex flex-wrap gap-2">
        <a href="{{ route('public.changelog') }}"
           class="filter-pill rounded-full px-4 py-2 text-[13px] font-medium {{ !$category ? 'filter-pill-active bg-gray-900 text-white' : 'filter-pill-inactive bg-white text-gray-500 hover:text-gray-700' }}">
            All
        </a>
        @foreach(['new' => 'New', 'improved' => 'Improved', 'fixed' => 'Fixed', 'performance' => 'Performance', 'security' => 'Security'] as $cat => $label)
            <a href="{{ route('public.changelog', ['category' => $cat]) }}"
               class="filter-pill rounded-full px-4 py-2 text-[13px] font-medium {{ $category === $cat ? 'filter-pill-active bg-gray-900 text-white' : 'filter-pill-inactive bg-white text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Posts --}}
    @if($posts->isEmpty())
        <div class="empty-state py-24 text-center">
            <div class="empty-icon-ring mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-gray-50 to-gray-100/50 ring-1 ring-gray-200/50">
                <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900">No updates yet</h3>
            <p class="mx-auto mt-2 max-w-xs text-sm leading-relaxed text-gray-400">Check back soon for the latest news and improvements.</p>
        </div>
    @else
        <div class="post-list relative space-y-0">
            @foreach($posts as $index => $post)
                <article class="post-item group relative pl-8 pb-10">
                    {{-- Timeline dot --}}
                    <div class="timeline-dot"></div>
                    {{-- Timeline line --}}
                    @if(!$loop->last)
                        <div class="timeline-line"></div>
                    @endif

                    {{-- Post card --}}
                    <div class="post-card rounded-2xl border border-gray-200/60 bg-white p-6 shadow-sm category-border-{{ $post->category }}">
                        <div class="flex items-center gap-3 text-sm">
                            <x-category-badge :category="$post->category" />
                            <span class="text-gray-200">&middot;</span>
                            <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-xs font-medium text-gray-400">
                                {{ $post->published_at->format('M j, Y') }}
                            </time>
                        </div>

                        <h2 class="mt-3 text-[1.125rem] font-semibold leading-snug tracking-tight text-gray-900">
                            <a href="{{ route('public.changelog.show', $post) }}" class="hover:text-indigo-600 transition-colors duration-200">
                                {{ $post->title }}
                            </a>
                        </h2>

                        @if($post->teaser)
                            <p class="mt-2.5 text-[0.9375rem] leading-relaxed text-gray-500">{{ $post->teaser }}</p>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('public.changelog.show', $post) }}" class="read-more-link group inline-flex items-center gap-1.5 text-sm font-medium text-indigo-500 transition-colors duration-200">
                                Read more <span class="read-more-arrow">&rarr;</span>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrapper mt-14">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    @endif
</x-layouts.public>
