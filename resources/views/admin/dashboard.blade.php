<x-layouts.app title="Dashboard">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-2 text-sm text-gray-600">Welcome to LoggyShip. This is your control center.</p>

    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Pending Drafts --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Open Drafts</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\Draft::whereIn('status', ['draft', 'review'])->count() }}</p>
        </div>

        {{-- Published Posts --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Published</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\Post::where('is_published', true)->count() }}</p>
        </div>

        {{-- Source Items --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Source Items</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ \App\Models\SourceItem::count() }}</p>
        </div>

        {{-- Last Sync --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Last Sync</p>
            @php $lastSync = \App\Models\Repository::max('last_synced_at'); @endphp
            <p class="mt-2 text-lg font-bold text-gray-900">{{ $lastSync ? \Carbon\Carbon::parse($lastSync)->diffForHumans() : 'Never' }}</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('admin.repositories.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Sync Repository
            </a>
            <a href="{{ route('admin.drafts.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Review Drafts
            </a>
            <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Settings
            </a>
        </div>
    </div>
</x-layouts.app>
