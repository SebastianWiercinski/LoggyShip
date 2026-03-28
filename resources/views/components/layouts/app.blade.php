<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — LoggyShip</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="hidden w-64 flex-shrink-0 border-r border-gray-200 bg-white lg:block">
            <div class="flex h-16 items-center border-b border-gray-200 px-6">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-indigo-600">LoggyShip</a>
            </div>
            <nav class="mt-4 space-y-1 px-3">
                <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-link>
                <x-nav-link href="{{ route('admin.drafts.index') }}" :active="request()->routeIs('admin.drafts.*')">Drafts</x-nav-link>
                <x-nav-link href="{{ route('admin.posts.index') }}" :active="request()->routeIs('admin.posts.*')">Published</x-nav-link>
                <x-nav-link href="{{ route('admin.repositories.index') }}" :active="request()->routeIs('admin.repositories.*')">Repositories</x-nav-link>
                <x-nav-link href="{{ route('admin.brand-voices.index') }}" :active="request()->routeIs('admin.brand-voices.*')">Brand Voice</x-nav-link>
                <x-nav-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')">Settings</x-nav-link>
            </nav>
            <div class="absolute bottom-4 left-0 w-64 px-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-600 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex flex-1 flex-col">
            {{-- Mobile header --}}
            <header class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 lg:hidden">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-indigo-600">LoggyShip</a>
            </header>

            <main class="flex-1 p-6 lg:p-8">
                @if(session('success'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
