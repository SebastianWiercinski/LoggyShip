<x-layouts.app title="Draft: {{ $draft->title }}">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .action-btn {
            transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .action-btn:hover {
            transform: translateY(-1px);
        }
    </style>

    {{-- Back navigation --}}
    <div class="animate-fade-in mb-6">
        <a href="{{ route('admin.drafts.index') }}" class="group inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
            <svg class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            Back to Drafts
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="animate-fade-in rounded-2xl border border-gray-200/80 bg-white shadow-sm overflow-hidden">
                {{-- Header bar --}}
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <x-category-badge :category="$draft->category" />

                        @if($draft->is_release_note)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700 ring-1 ring-inset ring-violet-500/20">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
                                Release Note v{{ $draft->version }}
                            </span>
                        @endif

                        @if($draft->confidence_score)
                            <div class="flex items-center gap-2 rounded-full bg-white px-3 py-1 ring-1 ring-inset ring-gray-200">
                                <span class="text-xs font-medium text-gray-500">Confidence</span>
                                <div class="h-1.5 w-12 overflow-hidden rounded-full bg-gray-100">
                                    <div class="h-full rounded-full
                                        {{ $draft->confidence_score >= 0.7 ? 'bg-gradient-to-r from-emerald-400 to-green-500' : '' }}
                                        {{ $draft->confidence_score >= 0.4 && $draft->confidence_score < 0.7 ? 'bg-gradient-to-r from-amber-400 to-yellow-500' : '' }}
                                        {{ $draft->confidence_score < 0.4 ? 'bg-gradient-to-r from-red-400 to-rose-500' : '' }}
                                    " style="width: {{ round($draft->confidence_score * 100) }}%"></div>
                                </div>
                                <span class="text-xs font-bold tabular-nums
                                    {{ $draft->confidence_score >= 0.7 ? 'text-emerald-600' : '' }}
                                    {{ $draft->confidence_score >= 0.4 && $draft->confidence_score < 0.7 ? 'text-amber-600' : '' }}
                                    {{ $draft->confidence_score < 0.4 ? 'text-red-600' : '' }}
                                ">{{ round($draft->confidence_score * 100) }}%</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-6 lg:p-8">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $draft->title }}</h1>

                    @if($draft->teaser)
                        <p class="mt-3 text-base text-gray-500 italic leading-relaxed border-l-2 border-gray-200 pl-4">{{ $draft->teaser }}</p>
                    @endif

                    <div class="mt-8 prose prose-sm prose-gray max-w-none
                        prose-headings:font-bold prose-headings:tracking-tight
                        prose-a:text-indigo-600 prose-a:no-underline hover:prose-a:underline
                        prose-code:rounded prose-code:bg-gray-100 prose-code:px-1.5 prose-code:py-0.5 prose-code:text-sm prose-code:font-medium prose-code:text-gray-800 prose-code:before:content-[''] prose-code:after:content-['']
                        prose-pre:rounded-xl prose-pre:bg-gray-900 prose-pre:shadow-lg
                        prose-img:rounded-xl prose-img:shadow-md
                        prose-blockquote:border-indigo-200 prose-blockquote:bg-indigo-50/30 prose-blockquote:rounded-r-lg prose-blockquote:py-1
                    ">
                        {!! $draft->body_html ?: Str::markdown($draft->body_markdown) !!}
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            @if(in_array($draft->status, ['draft', 'review']))
                <div class="animate-fade-in delay-1 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('admin.drafts.publish', $draft) }}">
                        @csrf
                        <button type="submit" class="action-btn inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-emerald-200/50 hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-200/60">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Publish
                        </button>
                    </form>

                    <a href="{{ route('admin.drafts.edit', $draft) }}" class="action-btn inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-blue-600 shadow-sm ring-1 ring-inset ring-blue-200 hover:bg-blue-50 hover:ring-blue-300 hover:shadow-md">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit
                    </a>

                    <form method="POST" action="{{ route('admin.drafts.regenerate', $draft) }}">
                        @csrf
                        <button type="submit" class="action-btn inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-amber-600 shadow-sm ring-1 ring-inset ring-amber-200 hover:bg-amber-50 hover:ring-amber-300 hover:shadow-md">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M21.015 4.356v4.992"/></svg>
                            Regenerate
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.drafts.discard', $draft) }}" onsubmit="return confirm('Discard this draft?')">
                        @csrf
                        <button type="submit" class="action-btn inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-200 hover:bg-red-50 hover:ring-red-300 hover:shadow-md">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Discard
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            {{-- Status card --}}
            <div class="animate-fade-in delay-1 rounded-2xl border border-gray-200/80 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 bg-gray-50/50 px-5 py-3">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Status</h3>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2.5">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-semibold
                            {{ $draft->status === 'draft' ? 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-500/20' : '' }}
                            {{ $draft->status === 'review' ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-500/20' : '' }}
                            {{ $draft->status === 'published' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-500/20' : '' }}
                            {{ $draft->status === 'discarded' ? 'bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-500/20' : '' }}
                        ">
                            <span class="h-2 w-2 rounded-full
                                {{ $draft->status === 'draft' ? 'bg-amber-500' : '' }}
                                {{ $draft->status === 'review' ? 'bg-blue-500' : '' }}
                                {{ $draft->status === 'published' ? 'bg-emerald-500' : '' }}
                                {{ $draft->status === 'discarded' ? 'bg-gray-400' : '' }}
                            "></span>
                            {{ ucfirst($draft->status) }}
                        </span>
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-sm text-gray-500">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Created {{ $draft->created_at->diffForHumans() }}
                    </div>
                    <p class="mt-1 text-xs text-gray-400 pl-6">{{ $draft->created_at->format('M j, Y \a\t g:i A') }}</p>
                </div>
            </div>

            {{-- Source Items --}}
            @if($draft->sourceItems->isNotEmpty())
                <div class="animate-fade-in delay-2 rounded-2xl border border-gray-200/80 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-5 py-3 flex items-center justify-between">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Source Items</h3>
                        <span class="inline-flex items-center justify-center rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-bold text-indigo-600 ring-1 ring-inset ring-indigo-500/20">{{ $draft->sourceItems->count() }}</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($draft->sourceItems as $item)
                            <div class="px-5 py-3 flex items-start gap-3 group/item hover:bg-gray-50/50 transition-colors">
                                <span class="mt-0.5 inline-flex items-center justify-center rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ $item->type }}</span>
                                <div class="flex-1 min-w-0">
                                    @if($item->url)
                                        <a href="{{ $item->url }}" target="_blank" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors line-clamp-2">
                                            {{ Str::limit($item->title, 60) }}
                                            <svg class="inline h-3 w-3 ml-0.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                        </a>
                                    @else
                                        <span class="text-sm font-medium text-gray-700 line-clamp-2">{{ Str::limit($item->title, 60) }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Brand Voice --}}
            @if($draft->brandVoice)
                <div class="animate-fade-in delay-2 rounded-2xl border border-gray-200/80 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-5 py-3">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Brand Voice</h3>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-400 to-purple-500 shadow-sm">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $draft->brandVoice->name }}</p>
                                <p class="text-xs text-gray-500">{{ $draft->brandVoice->language }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
