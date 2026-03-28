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
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-white text-gray-900 antialiased">
    <header class="border-b border-gray-100">
        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6">
            <div class="flex items-center justify-between">
                <a href="{{ url('/updates') }}" class="flex items-center gap-3">
                    @if(!empty($logoUrl))
                        <img src="{{ $logoUrl }}" alt="{{ $siteName ?? 'LoggyShip' }}" class="h-8 w-8">
                    @endif
                    <span class="text-lg font-semibold">{{ $siteName ?? 'Product Updates' }}</span>
                </a>
                <a href="{{ url('/updates/feed.xml') }}" class="text-sm text-gray-500 hover:text-gray-700">RSS</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6">
        {{ $slot }}
    </main>

    <footer class="border-t border-gray-100">
        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6">
            <p class="text-center text-xs text-gray-400">Powered by LoggyShip</p>
        </div>
    </footer>
</body>
</html>
