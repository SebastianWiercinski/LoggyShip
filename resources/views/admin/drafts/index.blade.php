<x-layouts.app title="Drafts">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Drafts</h1>
    </div>

    {{-- Filter --}}
    <div class="mt-4 flex gap-2">
        <a href="{{ route('admin.drafts.index') }}" class="rounded-lg px-3 py-1.5 text-sm {{ !$status ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">All</a>
        <a href="{{ route('admin.drafts.index', ['status' => 'draft']) }}" class="rounded-lg px-3 py-1.5 text-sm {{ $status === 'draft' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Draft</a>
        <a href="{{ route('admin.drafts.index', ['status' => 'review']) }}" class="rounded-lg px-3 py-1.5 text-sm {{ $status === 'review' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Review</a>
        <a href="{{ route('admin.drafts.index', ['status' => 'published']) }}" class="rounded-lg px-3 py-1.5 text-sm {{ $status === 'published' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Published</a>
        <a href="{{ route('admin.drafts.index', ['status' => 'discarded']) }}" class="rounded-lg px-3 py-1.5 text-sm {{ $status === 'discarded' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">Discarded</a>
    </div>

    @if($drafts->isEmpty())
        <div class="mt-8 rounded-xl border border-gray-200 bg-white p-8 text-center">
            <p class="text-gray-500">No drafts yet. Sync a repository and generate drafts to get started.</p>
        </div>
    @else
        <div class="mt-6 space-y-3">
            @foreach($drafts as $draft)
                <a href="{{ route('admin.drafts.show', $draft) }}" class="block rounded-xl border border-gray-200 bg-white p-4 hover:border-indigo-300 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <x-category-badge :category="$draft->category" />
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ $draft->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $draft->status === 'review' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $draft->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $draft->status === 'discarded' ? 'bg-gray-100 text-gray-600' : '' }}
                                ">{{ ucfirst($draft->status) }}</span>
                            </div>
                            <h3 class="mt-1 font-medium text-gray-900">{{ $draft->title }}</h3>
                            @if($draft->teaser)
                                <p class="mt-1 text-sm text-gray-500">{{ Str::limit($draft->teaser, 120) }}</p>
                            @endif
                        </div>
                        @if($draft->confidence_score)
                            <span class="ml-4 text-xs text-gray-400">{{ round($draft->confidence_score * 100) }}%</span>
                        @endif
                    </div>
                    <p class="mt-2 text-xs text-gray-400">{{ $draft->created_at->diffForHumans() }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $drafts->links() }}
        </div>
    @endif
</x-layouts.app>
