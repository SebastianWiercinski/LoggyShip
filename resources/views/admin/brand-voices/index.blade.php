<x-layouts.app title="Brand Voices">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.4s ease-out both; }
    </style>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Brand Voices</h1>
            <p class="mt-1 text-sm text-gray-500">Manage voice profiles for AI-generated changelog content.</p>
        </div>
    </div>

    @if($voices->isEmpty())
        <div class="animate-fade-in mt-10 rounded-2xl border border-dashed border-gray-300 bg-white p-16 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-100 to-purple-50">
                <svg class="h-7 w-7 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z"/></svg>
            </div>
            <h3 class="mt-5 text-base font-semibold text-gray-900">No brand voices configured</h3>
            <p class="mx-auto mt-2 max-w-sm text-sm text-gray-500">Create a brand voice to control the tone and style of generated changelog entries.</p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach($voices as $voice)
                <div class="animate-fade-in group rounded-2xl border border-gray-200 bg-white p-6 transition-all duration-300 hover:-translate-y-0.5 hover:border-purple-200 hover:shadow-lg hover:shadow-purple-100/40">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0">
                            {{-- Language indicator --}}
                            <div class="mt-0.5 flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-violet-600 shadow-sm shadow-purple-200">
                                <span class="text-sm font-bold text-white uppercase">{{ $voice->language }}</span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <h3 class="text-[15px] font-semibold text-gray-900 transition-colors group-hover:text-purple-600">{{ $voice->name }}</h3>
                                    @if($voice->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-500 ring-1 ring-inset ring-gray-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            Inactive
                                        </span>
                                    @endif

                                    @if($voice->generated_profile)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                            Analyzed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                                            Not analyzed
                                        </span>
                                    @endif
                                </div>

                                @if($voice->generated_profile)
                                    <p class="mt-1.5 text-sm text-gray-500">{{ $voice->generated_profile['voice_summary'] ?? 'Profile generated' }}</p>
                                @else
                                    <p class="mt-1.5 text-sm text-gray-400">Run analysis to generate a voice profile from sample texts.</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center gap-1">
                            <a href="{{ route('admin.brand-voices.edit', $voice) }}"
                               class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-indigo-600 transition-colors hover:bg-indigo-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                Edit
                            </a>
                            @if(!$voice->is_active)
                                <form method="POST" action="{{ route('admin.brand-voices.activate', $voice) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-green-600 transition-colors hover:bg-green-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                        Activate
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.brand-voices.analyze', $voice) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-purple-600 transition-colors hover:bg-purple-50">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456Z"/></svg>
                                    Analyze
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.app>
