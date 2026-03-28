<x-layouts.app title="Drafts">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in { animation: fadeInUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .animate-fade-in-pure { animation: fadeIn 0.4s ease-out both; }
        .draft-card {
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .draft-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px -12px rgba(99, 102, 241, 0.15), 0 4px 12px -2px rgba(0, 0, 0, 0.05);
        }
        .tab-bar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .tab-bar::-webkit-scrollbar { display: none; }
        .tab-item {
            position: relative;
            transition: all 0.25s ease;
        }
        .tab-item.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 2px;
            background: linear-gradient(90deg, #6366f1, #818cf8);
            border-radius: 999px;
        }
    </style>

    {{-- Header --}}
    <div class="animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Drafts</h1>
                <p class="mt-1 text-sm text-gray-500">Review, edit, and publish AI-generated changelog entries.</p>
            </div>
        </div>
    </div>

    {{-- Tab-style filter bar --}}
    <div class="animate-fade-in mt-6 overflow-x-auto tab-bar">
        <div class="inline-flex items-center rounded-xl border border-gray-200/80 bg-white p-1.5 shadow-sm">
            <a href="{{ route('admin.drafts.index') }}"
               class="tab-item flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition-all duration-200
                      {{ !$status ? 'active bg-indigo-600 text-white shadow-md shadow-indigo-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                All
                <span class="inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-bold {{ !$status ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500' }}">{{ $drafts->total() }}</span>
            </a>
            @php
                $tabConfig = [
                    'draft'     => ['label' => 'Draft',     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>',     'dotColor' => 'bg-yellow-500'],
                    'review'    => ['label' => 'Review',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',  'dotColor' => 'bg-blue-500'],
                    'published' => ['label' => 'Published', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',                                                                                  'dotColor' => 'bg-green-500'],
                    'discarded' => ['label' => 'Discarded', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>', 'dotColor' => 'bg-gray-400'],
                ];
            @endphp
            @foreach($tabConfig as $key => $tab)
                <a href="{{ route('admin.drafts.index', ['status' => $key]) }}"
                   class="tab-item flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition-all duration-200
                          {{ $status === $key ? 'active bg-indigo-600 text-white shadow-md shadow-indigo-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">{!! $tab['icon'] !!}</svg>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    @if($drafts->isEmpty())
        <div class="animate-fade-in mt-10 rounded-2xl border-2 border-dashed border-gray-200 bg-white p-16 text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-gray-50 to-gray-100 shadow-inner">
                <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <h3 class="mt-6 text-base font-semibold text-gray-900">No drafts found</h3>
            <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Sync a repository and generate drafts to get started. AI will analyze your commits and create changelog entries automatically.</p>
            <a href="{{ route('admin.repositories.index') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200/50 hover:bg-indigo-700 hover:shadow-lg transition-all duration-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M21.015 4.356v4.992"/></svg>
                Sync Repositories
            </a>
        </div>
    @else
        <div class="mt-6 space-y-3">
            @foreach($drafts as $index => $draft)
                <a href="{{ route('admin.drafts.show', $draft) }}"
                   class="animate-fade-in draft-card group block rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm hover:border-indigo-200/60"
                   style="animation-delay: {{ $index * 0.05 }}s">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <x-category-badge :category="$draft->category" />

                                {{-- Status dot + label --}}
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $draft->status === 'draft' ? 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-500/20' : '' }}
                                    {{ $draft->status === 'review' ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-500/20' : '' }}
                                    {{ $draft->status === 'published' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-500/20' : '' }}
                                    {{ $draft->status === 'discarded' ? 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/20' : '' }}
                                ">
                                    <span class="h-1.5 w-1.5 rounded-full
                                        {{ $draft->status === 'draft' ? 'bg-amber-500' : '' }}
                                        {{ $draft->status === 'review' ? 'bg-blue-500' : '' }}
                                        {{ $draft->status === 'published' ? 'bg-emerald-500' : '' }}
                                        {{ $draft->status === 'discarded' ? 'bg-gray-400' : '' }}
                                    "></span>
                                    {{ ucfirst($draft->status) }}
                                </span>
                            </div>

                            <h3 class="mt-2.5 text-base font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200">{{ $draft->title }}</h3>

                            @if($draft->teaser)
                                <p class="mt-1.5 text-sm text-gray-500 line-clamp-2 leading-relaxed">{{ Str::limit($draft->teaser, 120) }}</p>
                            @endif

                            <div class="mt-3 flex items-center gap-4 text-xs text-gray-400">
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                    {{ $draft->created_at->format('M j, Y') }}
                                </span>
                                <span class="text-gray-300">&middot;</span>
                                <span>{{ $draft->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Confidence score --}}
                        <div class="shrink-0 flex flex-col items-end gap-3">
                            @if($draft->confidence_score)
                                <div class="text-right">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Confidence</span>
                                    <div class="mt-1.5 flex items-center gap-2.5">
                                        <div class="h-2 w-20 overflow-hidden rounded-full bg-gray-100">
                                            <div class="h-full rounded-full transition-all duration-700 ease-out
                                                {{ $draft->confidence_score >= 0.7 ? 'bg-gradient-to-r from-emerald-400 to-green-500' : '' }}
                                                {{ $draft->confidence_score >= 0.4 && $draft->confidence_score < 0.7 ? 'bg-gradient-to-r from-amber-400 to-yellow-500' : '' }}
                                                {{ $draft->confidence_score < 0.4 ? 'bg-gradient-to-r from-red-400 to-rose-500' : '' }}
                                            " style="width: {{ round($draft->confidence_score * 100) }}%"></div>
                                        </div>
                                        <span class="text-sm font-bold tabular-nums
                                            {{ $draft->confidence_score >= 0.7 ? 'text-emerald-600' : '' }}
                                            {{ $draft->confidence_score >= 0.4 && $draft->confidence_score < 0.7 ? 'text-amber-600' : '' }}
                                            {{ $draft->confidence_score < 0.4 ? 'text-red-600' : '' }}
                                        ">{{ round($draft->confidence_score * 100) }}%</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Arrow indicator --}}
                            <svg class="h-5 w-5 text-gray-300 transition-all duration-300 group-hover:translate-x-1 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $drafts->links() }}
        </div>
    @endif
</x-layouts.app>
