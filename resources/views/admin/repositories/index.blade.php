<x-layouts.app title="Repositories">
    <h1 class="text-2xl font-bold text-gray-900">Repositories</h1>

    @if($repositories->isEmpty())
        <div class="mt-8 rounded-xl border border-gray-200 bg-white p-8 text-center">
            <p class="text-gray-500">No repositories connected. Run the setup wizard first.</p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach($repositories as $repo)
                <div class="rounded-xl border border-gray-200 bg-white p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $repo->full_name }}</h3>
                            @if($repo->description)
                                <p class="mt-1 text-sm text-gray-500">{{ Str::limit($repo->description, 100) }}</p>
                            @endif
                            <p class="mt-1 text-xs text-gray-400">
                                Branch: {{ $repo->default_branch }}
                                &middot; Last sync: {{ $repo->last_synced_at ? $repo->last_synced_at->diffForHumans() : 'Never' }}
                                &middot; Items: {{ $repo->sourceItems()->count() }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.repositories.toggle', $repo) }}">
                                @csrf
                                <button type="submit" class="rounded-lg px-3 py-1.5 text-sm {{ $repo->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $repo->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Sync settings --}}
                    <div class="mt-4 flex flex-wrap items-center gap-4 border-t border-gray-100 pt-4">
                        <form method="POST" action="{{ route('admin.repositories.settings', $repo) }}" class="flex items-center gap-4">
                            @csrf
                            @method('PUT')
                            <label class="flex items-center gap-1.5 text-sm text-gray-600">
                                <input type="checkbox" name="sync_commits" value="1" {{ $repo->sync_commits ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600">
                                Commits
                            </label>
                            <label class="flex items-center gap-1.5 text-sm text-gray-600">
                                <input type="checkbox" name="sync_prs" value="1" {{ $repo->sync_prs ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600">
                                PRs
                            </label>
                            <label class="flex items-center gap-1.5 text-sm text-gray-600">
                                <input type="checkbox" name="sync_releases" value="1" {{ $repo->sync_releases ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600">
                                Releases
                            </label>
                            <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800">Save</button>
                        </form>

                        <form method="POST" action="{{ route('admin.repositories.sync', $repo) }}">
                            @csrf
                            <button type="submit" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700">
                                Sync Now
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.app>
