<x-layouts.app title="Drafts">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.4s ease-out both; }
    </style>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Drafts</h1>
            <p class="mt-1 text-sm text-gray-500">Review, edit, and publish AI-generated changelog entries.</p>
        </div>
    </div>

    {{-- Tab-style filter bar --}}
    <div class="mt-6 inline-flex rounded-xl border border-gray-200 bg-white p-1 shadow-sm">
        <a href="{{ route('admin.drafts.index') }}"
           class="rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200
                  {{ !$status ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            All
            <span class="ml-1.5 inline-flex items-center justify-center rounded-full px-1.5 py-0.5 text-xs {{ !$status ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500' }}">{{ $drafts->total() }}</span>
        </a>
        @foreach(['draft' => 'Draft', 'review' => 'Review', 'published' => 'Published', 'discarded' => 'Discarded'] as $key => $label)
            <a href="{{ route('admin.drafts.index', ['status' => $key]) }}"
               class="rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200
                      {{ $status === $key ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if($drafts->isEmpty())
        <div class="animate-fade-in mt-10 rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100">
                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <h3 class="mt-4 text-sm font-semibold text-gray-900">No drafts yet</h3>
            <p class="mt-2 text-sm text-gray-500">Sync a repository and generate drafts to get started.</p>
            <a href="{{ route('admin.repositories.index') }}" class="mt-5 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M21.015 4.356v4.992"/></svg>
                Sync Repositories
            </a>
        </div>
    @else
        <div class="mt-6 space-y-3">
            @foreach($drafts as $draft)
                <a href="{{ route('admin.drafts.show', $draft) }}"
                   class="animate-fade-in group block rounded-2xl border border-gray-200 bg-white p-5 transition-all duration-300 hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-100/40">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <x-category-badge :category="$draft->category" />

                                {{-- Status dot + label --}}
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $draft->status === 'draft' ? 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20' : '' }}
                                    {{ $draft->status === 'review' ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20' : '' }}
                                    {{ $draft->status === 'published' ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20' : '' }}
                                    {{ $draft->status === 'discarded' ? 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/20' : '' }}
                                ">
                                    <span class="h-1.5 w-1.5 rounded-full
                                        {{ $draft->status === 'draft' ? 'bg-yellow-500' : '' }}
                                        {{ $draft->status === 'review' ? 'bg-blue-500' : '' }}
                                        {{ $draft->status === 'published' ? 'bg-green-500' : '' }}
                                        {{ $draft->status === 'discarded' ? 'bg-gray-400' : '' }}
                                    "></span>
                                    {{ ucfirst($draft->status) }}
                                </span>
                            </div>

                            <h3 class="mt-2 font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $draft->title }}</h3>

                            @if($draft->teaser)
                                <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ Str::limit($draft->teaser, 120) }}</p>
                            @endif

                            <div class="mt-3 flex items-center gap-4 text-xs text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $draft->created_at->format('M j, Y') }}
                                </span>
                                <span>{{ $draft->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Confidence score --}}
                        @if($draft->confidence_score)
                            <div class="shrink-0 text-right">
                                <span class="text-xs font-medium text-gray-500">Confidence</span>
                                <div class="mt-1 flex items-center gap-2">
                                    <div class="h-1.5 w-16 overflow-hidden rounded-full bg-gray-100">
                                        <div class="h-full rounded-full transition-all duration-500
                                            {{ $draft->confidence_score >= 0.7 ? 'bg-gradient-to-r from-emerald-400 to-green-500' : '' }}
                                            {{ $draft->confidence_score >= 0.4 && $draft->confidence_score < 0.7 ? 'bg-gradient-to-r from-amber-400 to-yellow-500' : '' }}
                                            {{ $draft->confidence_score < 0.4 ? 'bg-gradient-to-r from-red-400 to-rose-500' : '' }}
                                        " style="width: {{ round($draft->confidence_score * 100) }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700">{{ round($draft->confidence_score * 100) }}%</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $drafts->links() }}
        </div>
    @endif
</x-layouts.app>
