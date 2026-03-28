<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Updates' }} — {{ $siteName ?? 'LoggyShip' }}</title>
    @if(!empty($seoDescription))
        <meta name="description" content="{{ $seoDescription }}">
    @endif
    <meta property="og:title" content="{{ $title ?? 'Updates' }} — {{ $siteName ?? 'LoggyShip' }}">
    @if(!empty($seoDescription))
        <meta property="og:description" content="{{ $seoDescription }}">
    @endif
    <link rel="alternate" type="application/rss+xml" title="RSS Feed" href="{{ url('/updates/feed.xml') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,450;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        *, *::before, *::after {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* --- Animations --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes subtleFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2px); }
        }

        .page-enter {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* --- Header --- */
        .header-gradient {
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(99, 102, 241, 0.07) 0%, transparent 70%),
                radial-gradient(ellipse 60% 40% at 80% 0%, rgba(168, 85, 247, 0.05) 0%, transparent 60%),
                radial-gradient(ellipse 50% 35% at 20% 0%, rgba(236, 72, 153, 0.04) 0%, transparent 50%);
            backdrop-filter: saturate(1.2);
        }

        .header-nav {
            animation: fadeIn 0.5s ease forwards;
        }

        .site-logo {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        }

        .group:hover .site-logo {
            transform: scale(1.08) rotate(-1deg);
            box-shadow: 0 2px 12px rgba(99, 102, 241, 0.15);
        }

        .site-name {
            background: linear-gradient(135deg, #111827 0%, #374151 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .rss-link {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid transparent;
        }

        .rss-link:hover {
            background-color: rgba(99, 102, 241, 0.06);
            border-color: rgba(99, 102, 241, 0.12);
            color: #6366f1;
            transform: translateY(-1px);
        }

        /* --- Footer --- */
        .footer-brand {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% 100%;
            animation: shimmer 4s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .footer-divider {
            background: linear-gradient(90deg, transparent, #e5e7eb 30%, #e5e7eb 70%, transparent);
            height: 1px;
        }

        /* --- Scrollbar (Webkit) --- */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* --- Selection --- */
        ::selection {
            background: rgba(99, 102, 241, 0.15);
            color: #312e81;
        }
    </style>
</head>
<body class="min-h-screen bg-[#fafafa] text-gray-900 antialiased">
    <header class="header-gradient sticky top-0 z-50 border-b border-gray-200/60 bg-white/80 backdrop-blur-xl">
        <div class="header-nav mx-auto max-w-3xl px-5 py-4 sm:px-8">
            <div class="flex items-center justify-between">
                <a href="{{ url('/updates') }}" class="group flex items-center gap-3">
                    @if(!empty($logoUrl))
                        <img src="{{ $logoUrl }}" alt="{{ $siteName ?? 'LoggyShip' }}" class="site-logo h-8 w-8 rounded-xl shadow-sm ring-1 ring-black/[0.04]">
                    @else
                        <div class="site-logo flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-sm">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                    @endif
                    <span class="site-name text-[17px] font-semibold tracking-tight">{{ $siteName ?? 'Product Updates' }}</span>
                </a>
                <a href="{{ url('/updates/feed.xml') }}" class="rss-link inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-medium text-gray-400">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 5c7.18 0 13 5.82 13 13M6 11a7 7 0 017 7m-6 0a1 1 0 11-2 0 1 1 0 012 0z" />
                    </svg>
                    RSS
                </a>
            </div>
        </div>
    </header>

    <main class="page-enter mx-auto max-w-3xl px-5 py-12 sm:px-8 sm:py-14">
        {{ $slot }}
    </main>

    <footer class="mt-8">
        <div class="mx-auto max-w-3xl px-5 sm:px-8">
            <div class="footer-divider"></div>
            <div class="py-10">
                <p class="text-center text-[11px] font-medium tracking-widest text-gray-300 uppercase">
                    Powered by <span class="footer-brand font-bold">LoggyShip</span>
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
