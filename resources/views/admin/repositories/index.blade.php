<x-layouts.app title="Repositories">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.4s ease-out both; }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }
    </style>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Repositories</h1>
            <p class="mt-1 text-sm text-gray-500">Manage connected GitHub repositories and sync settings.</p>
        </div>
    </div>

    @if($repositories->isEmpty())
        <div class="animate-fade-in mt-10 rounded-2xl border border-dashed border-gray-300 bg-white p-16 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-gray-100 to-gray-50">
                <svg class="h-7 w-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125v-3.75"/></svg>
            </div>
            <h3 class="mt-5 text-base font-semibold text-gray-900">No repositories connected</h3>
            <p class="mx-auto mt-2 max-w-sm text-sm text-gray-500">Run the setup wizard to connect your GitHub repositories and start syncing.</p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach($repositories as $repo)
                <div class="animate-fade-in group rounded-2xl border border-gray-200 bg-white transition-all duration-300 hover:border-gray-300 hover:shadow-lg hover:shadow-gray-100/50">
                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-4 p-6 pb-0">
                        <div class="flex items-start gap-3.5 min-w-0">
                            <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-gray-800 to-gray-900 shadow-sm">
                                <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <h3 class="truncate text-[15px] font-semibold text-gray-900">{{ $repo->full_name }}</h3>
                                    <form method="POST" action="{{ route('admin.repositories.toggle', $repo) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium transition-colors
                                            {{ $repo->is_active
                                                ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 hover:bg-green-100'
                                                : 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-200 hover:bg-gray-100' }}">
                                            @if($repo->is_active)
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse-dot"></span>
                                            @else
                                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            @endif
                                            {{ $repo->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </div>
                                @if($repo->description)
                                    <p class="mt-1 text-sm text-gray-500">{{ Str::limit($repo->description, 100) }}</p>
                                @endif
                                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-400">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5-4.5L16.5 16.5m0 0L12 12m4.5 4.5V3"/></svg>
                                        {{ $repo->default_branch }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                        {{ $repo->last_synced_at ? $repo->last_synced_at->diffForHumans() : 'Never synced' }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375"/></svg>
                                        {{ $repo->sourceItems()->count() }} items
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sync settings --}}
                    <div class="mx-6 mt-4 mb-6 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-gray-100 bg-gray-50/70 p-4">
                        <form method="POST" action="{{ route('admin.repositories.settings', $repo) }}" class="flex flex-wrap items-center gap-4">
                            @csrf
                            @method('PUT')
                            <span class="text-xs font-medium uppercase tracking-wider text-gray-400">Sync</span>
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700 transition-colors hover:border-indigo-300 hover:bg-indigo-50/50 has-[:checked]:border-indigo-300 has-[:checked]:bg-indigo-50 has-[:checked]:text-indigo-700">
                                <input type="checkbox" name="sync_commits" value="1" {{ $repo->sync_commits ? 'checked' : '' }}
                                    class="h-3.5 w-3.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                Commits
                            </label>
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700 transition-colors hover:border-indigo-300 hover:bg-indigo-50/50 has-[:checked]:border-indigo-300 has-[:checked]:bg-indigo-50 has-[:checked]:text-indigo-700">
                                <input type="checkbox" name="sync_prs" value="1" {{ $repo->sync_prs ? 'checked' : '' }}
                                    class="h-3.5 w-3.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                PRs
                            </label>
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700 transition-colors hover:border-indigo-300 hover:bg-indigo-50/50 has-[:checked]:border-indigo-300 has-[:checked]:bg-indigo-50 has-[:checked]:text-indigo-700">
                                <input type="checkbox" name="sync_releases" value="1" {{ $repo->sync_releases ? 'checked' : '' }}
                                    class="h-3.5 w-3.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                Releases
                            </label>
                            <button type="submit" class="rounded-lg px-3 py-1.5 text-sm font-medium text-indigo-600 transition-colors hover:bg-indigo-50">
                                Save
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.repositories.sync', $repo) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-indigo-200 transition-all duration-200 hover:from-indigo-700 hover:to-indigo-800 hover:shadow-md active:scale-[0.97]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182M21.015 4.356v4.992"/></svg>
                                Sync Now
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.app>
