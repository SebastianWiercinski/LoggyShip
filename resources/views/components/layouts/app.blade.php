<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — LoggyShip</title>

    {{-- Inter font (300–800) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased font-sans" x-data="{ sidebarOpen: false }">

    {{-- ─── Mobile sidebar overlay ─── --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
         @click="sidebarOpen = false"
         x-cloak>
    </div>

    {{-- ─── Mobile sidebar ─── --}}
    <aside x-show="sidebarOpen"
           x-transition:enter="transition-transform ease-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition-transform ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 z-50 w-72 gradient-sidebar shadow-2xl shadow-black/30 lg:hidden"
           x-cloak>
        <div class="flex h-full flex-col">
            {{-- Logo bar --}}
            <div class="flex h-16 items-center justify-between px-5 border-b border-white/[0.06]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    {{-- Ship icon --}}
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500/15 ring-1 ring-indigo-400/20">
                        <svg class="h-5 w-5 text-indigo-400" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 22L16 26L28 22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 20C6 20 8 12 16 8C24 12 26 20 26 20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 8V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M16 4L20 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <circle cx="16" cy="16" r="2" fill="currentColor" opacity="0.4"/>
                        </svg>
                    </div>
                    <span class="text-[15px] font-bold text-white tracking-tight">LoggyShip</span>
                </a>
                <button @click="sidebarOpen = false" class="rounded-lg p-1.5 text-slate-400 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 space-y-1 px-3 py-4 overflow-y-auto sidebar-scroll">
                <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="dashboard">Dashboard</x-nav-link>
                <x-nav-link href="{{ route('admin.drafts.index') }}" :active="request()->routeIs('admin.drafts.*')" icon="drafts">Drafts</x-nav-link>
                <x-nav-link href="{{ route('admin.posts.index') }}" :active="request()->routeIs('admin.posts.*')" icon="published">Published</x-nav-link>
                <x-nav-link href="{{ route('admin.repositories.index') }}" :active="request()->routeIs('admin.repositories.*')" icon="repositories">Repositories</x-nav-link>
                <x-nav-link href="{{ route('admin.brand-voices.index') }}" :active="request()->routeIs('admin.brand-voices.*')" icon="brand-voice">Brand Voice</x-nav-link>
                <x-nav-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')" icon="settings">Settings</x-nav-link>
            </nav>

            {{-- User / Logout --}}
            <div class="border-t border-white/[0.06] p-4">
                <div class="flex items-center gap-3 mb-3 px-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500/25 to-violet-500/25 text-sm font-semibold text-indigo-300 ring-1 ring-indigo-400/20">
                        {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/[0.04] hover:text-white transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex min-h-screen">
        {{-- ─── Desktop Sidebar ─── --}}
        <aside class="hidden lg:flex lg:w-[17rem] lg:flex-col lg:fixed lg:inset-y-0 gradient-sidebar shadow-xl shadow-black/10">
            <div class="flex h-full flex-col">
                {{-- Logo --}}
                <div class="flex h-16 items-center px-5 border-b border-white/[0.06]">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500/15 ring-1 ring-indigo-400/20 transition-all duration-300 group-hover:bg-indigo-500/25 group-hover:ring-indigo-400/30 group-hover:shadow-lg group-hover:shadow-indigo-500/10">
                            <svg class="h-5 w-5 text-indigo-400 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 22L16 26L28 22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6 20C6 20 8 12 16 8C24 12 26 20 26 20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16 8V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M16 4L20 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <circle cx="16" cy="16" r="2" fill="currentColor" opacity="0.4"/>
                            </svg>
                        </div>
                        <span class="text-[15px] font-bold text-white tracking-tight">LoggyShip</span>
                    </a>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-3 py-5 overflow-y-auto sidebar-scroll">
                    <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500/80">Content</p>
                    <div class="space-y-0.5 stagger-children">
                        <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="dashboard">Dashboard</x-nav-link>
                        <x-nav-link href="{{ route('admin.drafts.index') }}" :active="request()->routeIs('admin.drafts.*')" icon="drafts">Drafts</x-nav-link>
                        <x-nav-link href="{{ route('admin.posts.index') }}" :active="request()->routeIs('admin.posts.*')" icon="published">Published</x-nav-link>
                    </div>

                    <p class="mb-2 mt-7 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500/80">Manage</p>
                    <div class="space-y-0.5 stagger-children">
                        <x-nav-link href="{{ route('admin.repositories.index') }}" :active="request()->routeIs('admin.repositories.*')" icon="repositories">Repositories</x-nav-link>
                        <x-nav-link href="{{ route('admin.brand-voices.index') }}" :active="request()->routeIs('admin.brand-voices.*')" icon="brand-voice">Brand Voice</x-nav-link>
                        <x-nav-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')" icon="settings">Settings</x-nav-link>
                    </div>
                </nav>

                {{-- User / Logout --}}
                <div class="border-t border-white/[0.06] p-4">
                    <div class="flex items-center gap-3 mb-3 px-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500/25 to-violet-500/25 text-sm font-semibold text-indigo-300 ring-1 ring-indigo-400/20 shadow-sm shadow-indigo-500/10">
                            {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/[0.04] hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ─── Main content ─── --}}
        <div class="flex flex-1 flex-col lg:pl-[17rem]">
            {{-- Top bar --}}
            <header class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b border-gray-200/60 bg-white/70 backdrop-blur-xl px-4 sm:px-6 lg:px-8">
                {{-- Mobile hamburger --}}
                <button @click="sidebarOpen = true" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 lg:hidden transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>

                {{-- Breadcrumb / page title --}}
                <div class="flex items-center gap-2 min-w-0">
                    <nav class="flex items-center gap-1.5 text-sm">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-indigo-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                        </a>
                        <svg class="h-3.5 w-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        <span class="font-semibold text-gray-800 truncate">{{ $title ?? 'Dashboard' }}</span>
                    </nav>
                </div>
            </header>

            {{-- Page body --}}
            <main class="flex-1 gradient-main">
                <div class="px-4 py-6 sm:px-6 lg:px-8 animate-slide-up">
                    {{-- Flash: success --}}
                    @if(session('success'))
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 4500)"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200/60 bg-gradient-to-r from-emerald-50 to-teal-50 px-4 py-3 text-sm text-emerald-700 shadow-sm shadow-emerald-100/50">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100">
                                <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                            <span class="font-medium">{{ session('success') }}</span>
                            <button @click="show = false" class="ml-auto rounded-md p-1 text-emerald-400 hover:bg-emerald-100 hover:text-emerald-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @endif

                    {{-- Flash: error --}}
                    @if(session('error'))
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 6000)"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="mb-6 flex items-center gap-3 rounded-xl border border-red-200/60 bg-gradient-to-r from-red-50 to-rose-50 px-4 py-3 text-sm text-red-700 shadow-sm shadow-red-100/50">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-red-100">
                                <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                            </div>
                            <span class="font-medium">{{ session('error') }}</span>
                            <button @click="show = false" class="ml-auto rounded-md p-1 text-red-400 hover:bg-red-100 hover:text-red-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
