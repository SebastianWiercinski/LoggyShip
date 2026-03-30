<x-layouts.app title="Dashboard">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .delay-1 { animation-delay: 0.08s; }
        .delay-2 { animation-delay: 0.16s; }
        .delay-3 { animation-delay: 0.24s; }
        .delay-4 { animation-delay: 0.32s; }
        .delay-5 { animation-delay: 0.40s; }
        .delay-6 { animation-delay: 0.48s; }
        .stat-card {
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .stat-card:hover::before {
            opacity: 1;
        }
        .stat-card-amber::before { background: linear-gradient(90deg, #f59e0b, #eab308); }
        .stat-card-green::before { background: linear-gradient(90deg, #10b981, #22c55e); }
        .stat-card-blue::before { background: linear-gradient(90deg, #3b82f6, #6366f1); }
        .stat-card-purple::before { background: linear-gradient(90deg, #a855f7, #8b5cf6); }
        .icon-float {
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.4s ease;
        }
        .stat-card:hover .icon-float {
            transform: translateY(-2px) scale(1.05);
        }
        .action-card {
            position: relative;
            overflow: hidden;
        }
        .action-card::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: inherit;
        }
        .action-card:hover::after {
            opacity: 1;
        }
    </style>

    {{-- Welcome --}}
    <div class="animate-fade-in-up">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-200/50">
                @php
                    $hour = now()->hour;
                    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
                @endphp
                @if($hour < 12)
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                @elseif($hour < 17)
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                @else
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $greeting }}</h1>
                <p class="text-sm text-gray-500">Here's what's happening with your changelog.</p>
            </div>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Open Drafts --}}
        <div class="animate-fade-in-up delay-1 stat-card stat-card-amber group rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-100/60 hover:border-amber-200/60">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Open Drafts</p>
                    <p class="mt-3 text-4xl font-extrabold tracking-tight text-gray-900">{{ $openDrafts }}</p>
                    <p class="mt-1 text-xs text-gray-400">Awaiting review</p>
                </div>
                <div class="icon-float flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-yellow-500 shadow-md shadow-amber-200/50">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                </div>
            </div>
        </div>

        {{-- Published --}}
        <div class="animate-fade-in-up delay-2 stat-card stat-card-green group rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-green-100/60 hover:border-green-200/60">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Published</p>
                    <p class="mt-3 text-4xl font-extrabold tracking-tight text-gray-900">{{ $publishedPosts }}</p>
                    <p class="mt-1 text-xs text-gray-400">Live entries</p>
                </div>
                <div class="icon-float flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-green-500 shadow-md shadow-green-200/50">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Source Items --}}
        <div class="animate-fade-in-up delay-3 stat-card stat-card-blue group rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-100/60 hover:border-blue-200/60">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Source Items</p>
                    <p class="mt-3 text-4xl font-extrabold tracking-tight text-gray-900">{{ $sourceItems }}</p>
                    <p class="mt-1 text-xs text-gray-400">Commits & issues</p>
                </div>
                <div class="icon-float flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 shadow-md shadow-blue-200/50">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125v-3.75"/></svg>
                </div>
            </div>
        </div>

        {{-- Last Sync --}}
        <div class="animate-fade-in-up delay-4 stat-card stat-card-purple group rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-purple-100/60 hover:border-purple-200/60">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Last Sync</p>
                    <p class="mt-3 text-xl font-extrabold tracking-tight text-gray-900">{{ $lastSync ? \Carbon\Carbon::parse($lastSync)->diffForHumans() : 'Never' }}</p>
                    <p class="mt-1 text-xs text-gray-400">
                        @if($lastSync)
                            {{ \Carbon\Carbon::parse($lastSync)->format('M j, g:i A') }}
                        @else
                            No syncs yet
                        @endif
                    </p>
                </div>
                <div class="icon-float flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-400 to-violet-500 shadow-md shadow-purple-200/50">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M21.015 4.356v4.992"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- LLM Provider --}}
    <div class="animate-fade-in-up delay-5 mt-8 rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-gray-700 to-gray-900 shadow-md shadow-gray-300/40">
                @if(str_contains(strtolower($llmProvider), 'openai'))
                    <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M22.282 9.821a5.985 5.985 0 00-.516-4.91 6.046 6.046 0 00-6.51-2.9A6.065 6.065 0 0011.708.0a6.044 6.044 0 00-5.764 4.178 5.986 5.986 0 00-3.997 2.9 6.045 6.045 0 00.749 7.19 5.985 5.985 0 00.516 4.911 6.046 6.046 0 006.51 2.9A6.065 6.065 0 0013.292 24a6.044 6.044 0 005.764-4.178 5.986 5.986 0 003.997-2.9 6.045 6.045 0 00-.749-7.19h-.022z"/></svg>
                @elseif(str_contains(strtolower($llmProvider), 'anthropic'))
                    <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M13.827 3.52h3.603L24 20.48h-3.603l-6.57-16.96zm-7.258 0h3.767L16.906 20.48h-3.674l-1.508-4.01H5.248l-1.508 4.01H0L6.569 3.52zm1.04 3.878L4.836 14.164h5.347L7.609 7.398z"/></svg>
                @else
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/></svg>
                @endif
            </div>
            <div class="flex-1">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">LLM Provider</p>
                <p class="mt-0.5 text-sm font-bold text-gray-900">{{ ucfirst($llmProvider) }} <span class="font-normal text-gray-400">/</span> <span class="font-medium text-gray-600">{{ $llmModel }}</span></p>
            </div>
            <div class="flex h-8 items-center rounded-full bg-emerald-50 px-3 ring-1 ring-inset ring-emerald-500/20">
                <span class="mr-1.5 h-2 w-2 rounded-full bg-emerald-500" style="animation: pulse-soft 2s infinite;"></span>
                <span class="text-xs font-medium text-emerald-700">Active</span>
            </div>
        </div>
    </div>

    {{-- Action cards --}}
    <div class="animate-fade-in-up delay-6 mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('admin.repositories.index') }}" class="action-card group flex items-center gap-4 rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg shadow-indigo-200/50 transition-transform duration-300 group-hover:scale-110 group-hover:shadow-indigo-300/60">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M21.015 4.356v4.992"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Sync Repositories</p>
                <p class="mt-0.5 text-xs text-gray-500">Fetch latest commits and issues</p>
            </div>
            <svg class="ml-auto h-5 w-5 text-gray-300 transition-all duration-300 group-hover:translate-x-1 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </a>

        <a href="{{ route('admin.drafts.index') }}" class="action-card group flex items-center gap-4 rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-amber-200 hover:shadow-xl hover:shadow-amber-100/50">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 shadow-lg shadow-amber-200/50 transition-transform duration-300 group-hover:scale-110 group-hover:shadow-amber-300/60">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-900 group-hover:text-amber-600 transition-colors">Review Drafts</p>
                <p class="mt-0.5 text-xs text-gray-500">Review and publish draft posts</p>
            </div>
            <svg class="ml-auto h-5 w-5 text-gray-300 transition-all duration-300 group-hover:translate-x-1 group-hover:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </a>

        <a href="{{ route('admin.posts.create') }}" class="action-card group flex items-center gap-4 rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-100/50">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 shadow-lg shadow-emerald-200/50 transition-transform duration-300 group-hover:scale-110 group-hover:shadow-emerald-300/60">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">Manual Post</p>
                <p class="mt-0.5 text-xs text-gray-500">Write a changelog entry by hand</p>
            </div>
            <svg class="ml-auto h-5 w-5 text-gray-300 transition-all duration-300 group-hover:translate-x-1 group-hover:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </a>

        <a href="{{ route('admin.release-notes.create') }}" class="action-card group flex items-center gap-4 rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-violet-200 hover:shadow-xl hover:shadow-violet-100/50">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 shadow-lg shadow-violet-200/50 transition-transform duration-300 group-hover:scale-110 group-hover:shadow-violet-300/60">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-900 group-hover:text-violet-600 transition-colors">Release Notes</p>
                <p class="mt-0.5 text-xs text-gray-500">Generate notes for a complete release</p>
            </div>
            <svg class="ml-auto h-5 w-5 text-gray-300 transition-all duration-300 group-hover:translate-x-1 group-hover:text-violet-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </a>
    </div>
</x-layouts.app>
