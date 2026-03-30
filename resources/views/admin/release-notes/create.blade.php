<x-layouts.app title="Generate Release Notes">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
    </style>

    {{-- Back navigation --}}
    <div class="animate-fade-in mb-6">
        <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
            <svg class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            Back to Dashboard
        </a>
    </div>

    {{-- Header --}}
    <div class="animate-fade-in mb-8">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 shadow-lg shadow-violet-200/50">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Release Notes</h1>
                <p class="text-sm text-gray-500">Generate comprehensive release notes from your changes.</p>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Form --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.release-notes.generate') }}" class="animate-fade-in delay-1 rounded-2xl border border-gray-200/80 bg-white shadow-sm overflow-hidden">
                @csrf

                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h2 class="text-sm font-semibold text-gray-700">Configuration</h2>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Repository --}}
                    <div>
                        <label for="repository_id" class="block text-sm font-semibold text-gray-700">Repository</label>
                        <select name="repository_id" id="repository_id" class="mt-1.5 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">All Repositories</option>
                            @foreach($repositories as $repo)
                                <option value="{{ $repo->id }}">{{ $repo->full_name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-400">Select a specific repo or generate notes across all.</p>
                    </div>

                    {{-- Date Range --}}
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="since" class="block text-sm font-semibold text-gray-700">Since</label>
                            <input type="date" name="since" id="since" value="{{ $sinceDate }}" required
                                class="mt-1.5 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <p class="mt-1 text-xs text-gray-400">Auto-detected from last release.</p>
                        </div>
                        <div>
                            <label for="until" class="block text-sm font-semibold text-gray-700">Until</label>
                            <input type="date" name="until" id="until" value="{{ now()->format('Y-m-d') }}"
                                class="mt-1.5 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <p class="mt-1 text-xs text-gray-400">Defaults to today.</p>
                        </div>
                    </div>

                    {{-- Version --}}
                    <div>
                        <label for="version" class="block text-sm font-semibold text-gray-700">Version</label>
                        <div class="mt-1.5 relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 text-sm font-medium">v</span>
                            <input type="text" name="version" id="version" value="{{ $suggestedVersion }}" required
                                class="block w-full rounded-xl border-gray-300 pl-8 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="1.0.0">
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Auto-suggested based on last known version.</p>
                    </div>

                    @if($errors->any())
                        <div class="rounded-xl bg-red-50 p-4 ring-1 ring-inset ring-red-200">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 text-red-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                <div>
                                    @foreach($errors->all() as $error)
                                        <p class="text-sm text-red-700">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="border-t border-gray-100 bg-gray-50/30 px-6 py-4 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        <span class="font-semibold text-gray-700">{{ $itemCount }}</span> items available
                    </p>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-violet-200/50 hover:from-violet-700 hover:to-purple-700 hover:shadow-lg hover:shadow-violet-200/60 transition-all duration-200 hover:-translate-y-0.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
                        Generate Release Notes
                    </button>
                </div>
            </form>
        </div>

        {{-- Info sidebar --}}
        <div class="space-y-5">
            <div class="animate-fade-in delay-2 rounded-2xl border border-gray-200/80 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 bg-gray-50/50 px-5 py-3">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">How it works</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-xs font-bold text-violet-600">1</div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Gather changes</p>
                            <p class="text-xs text-gray-500">All user-facing items since the last release are collected.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-xs font-bold text-violet-600">2</div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Smart categorization</p>
                            <p class="text-xs text-gray-500">Items are grouped by type: new, improved, fixed, performance, security.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-xs font-bold text-violet-600">3</div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">AI generation</p>
                            <p class="text-xs text-gray-500">Your LLM writes structured release notes in your brand voice.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-xs font-bold text-violet-600">4</div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Review & publish</p>
                            <p class="text-xs text-gray-500">Edit the draft if needed, then publish to your changelog.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
