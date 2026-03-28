<x-layouts.app title="Dashboard">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>

    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Open Drafts</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $openDrafts }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Published</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $publishedPosts }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Source Items</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $sourceItems }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Last Sync</p>
            <p class="mt-2 text-lg font-bold text-gray-900">{{ $lastSync ? \Carbon\Carbon::parse($lastSync)->diffForHumans() : 'Never' }}</p>
        </div>
    </div>

    <div class="mt-8 rounded-xl border border-gray-200 bg-white p-6">
        <h2 class="text-sm font-medium text-gray-500">LLM Provider</h2>
        <p class="mt-1 text-sm text-gray-900">{{ ucfirst($llmProvider) }} / {{ $llmModel }}</p>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('admin.repositories.index') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Sync Repositories</a>
        <a href="{{ route('admin.drafts.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Review Drafts</a>
        <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Manual Post</a>
    </div>
</x-layouts.app>
