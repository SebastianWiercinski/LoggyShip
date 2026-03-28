<x-layouts.app title="Draft: {{ $draft->title }}">
    <div class="mb-4">
        <a href="{{ route('admin.drafts.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Back to Drafts</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main content --}}
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <div class="flex items-center gap-2 mb-4">
                    <x-category-badge :category="$draft->category" />
                    @if($draft->confidence_score)
                        <span class="text-xs text-gray-400">Confidence: {{ round($draft->confidence_score * 100) }}%</span>
                    @endif
                </div>

                <h1 class="text-xl font-bold text-gray-900">{{ $draft->title }}</h1>

                @if($draft->teaser)
                    <p class="mt-2 text-sm text-gray-600 italic">{{ $draft->teaser }}</p>
                @endif

                <div class="mt-6 prose prose-sm max-w-none">
                    {!! $draft->body_html ?: Str::markdown($draft->body_markdown) !!}
                </div>
            </div>

            {{-- Actions --}}
            @if(in_array($draft->status, ['draft', 'review']))
                <div class="mt-4 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('admin.drafts.publish', $draft) }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">Publish</button>
                    </form>
                    <a href="{{ route('admin.drafts.edit', $draft) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Edit</a>
                    <form method="POST" action="{{ route('admin.drafts.regenerate', $draft) }}">
                        @csrf
                        <button type="submit" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Regenerate</button>
                    </form>
                    <form method="POST" action="{{ route('admin.drafts.discard', $draft) }}" onsubmit="return confirm('Discard this draft?')">
                        @csrf
                        <button type="submit" class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Discard</button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <h3 class="text-sm font-medium text-gray-700">Status</h3>
                <p class="mt-1 text-sm text-gray-900">{{ ucfirst($draft->status) }}</p>
                <p class="mt-1 text-xs text-gray-400">Created {{ $draft->created_at->diffForHumans() }}</p>
            </div>

            @if($draft->sourceItems->isNotEmpty())
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <h3 class="text-sm font-medium text-gray-700">Source Items</h3>
                    <ul class="mt-2 space-y-2">
                        @foreach($draft->sourceItems as $item)
                            <li class="text-sm">
                                <span class="inline-block rounded bg-gray-100 px-1.5 py-0.5 text-xs text-gray-600">{{ $item->type }}</span>
                                @if($item->url)
                                    <a href="{{ $item->url }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">{{ Str::limit($item->title, 60) }}</a>
                                @else
                                    <span class="text-gray-900">{{ Str::limit($item->title, 60) }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($draft->brandVoice)
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <h3 class="text-sm font-medium text-gray-700">Brand Voice</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $draft->brandVoice->name }}</p>
                    <p class="mt-1 text-xs text-gray-400">Language: {{ $draft->brandVoice->language }}</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
