<x-layouts.app title="New Post">
    <div class="mb-4">
        <a href="{{ route('admin.posts.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Back to Posts</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6">
        <h1 class="text-xl font-bold text-gray-900">Create Manual Post</h1>

        <form method="POST" action="{{ route('admin.posts.store') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Teaser</label>
                <input type="text" name="teaser" value="{{ old('teaser') }}" maxlength="500"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @foreach(['new', 'improved', 'fixed', 'performance', 'security'] as $cat)
                        <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Body (Markdown)</label>
                <textarea name="body_markdown" rows="12" required
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono">{{ old('body_markdown') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">SEO Title (optional)</label>
                <input type="text" name="seo_title" value="{{ old('seo_title') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">SEO Description (optional)</label>
                <textarea name="seo_description" rows="2"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('seo_description') }}</textarea>
            </div>

            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Publish</button>
        </form>
    </div>
</x-layouts.app>
