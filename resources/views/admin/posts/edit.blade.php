<x-layouts.app title="Edit Post">
    <div class="mb-6">
        <a href="{{ route('admin.posts.index') }}" class="group inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition-colors hover:text-gray-900">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            Back to Posts
        </a>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 shadow-sm shadow-amber-200">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Edit Post</h1>
                    <p class="mt-0.5 text-sm text-gray-500">Update the content and metadata of this post.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required
                        class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20"
                        placeholder="What changed?">
                    @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="teaser" class="block text-sm font-semibold text-gray-700">Teaser</label>
                    <p class="mt-0.5 text-xs text-gray-400">A brief summary shown in the feed (max 500 chars)</p>
                    <input type="text" id="teaser" name="teaser" value="{{ old('teaser', $post->teaser) }}" maxlength="500"
                        class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20"
                        placeholder="One-liner summary of this update">
                    @error('teaser') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-700">Category</label>
                    <select id="category" name="category"
                        class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                        @foreach(['new', 'improved', 'fixed', 'performance', 'security'] as $cat)
                            <option value="{{ $cat }}" {{ $post->category === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="body_markdown" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <span>Body</span>
                        <span class="rounded-md bg-gray-100 px-1.5 py-0.5 text-[10px] font-medium uppercase tracking-wider text-gray-500">Markdown</span>
                    </label>
                    <p class="mt-0.5 text-xs text-gray-400">Write the full changelog content using Markdown syntax</p>
                    <div class="mt-1.5 overflow-hidden rounded-xl border border-gray-700 shadow-sm">
                        <div class="flex items-center gap-2 border-b border-gray-700 bg-gray-800 px-4 py-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-400/80"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-yellow-400/80"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-green-400/80"></span>
                            <span class="ml-2 text-xs text-gray-500">markdown</span>
                        </div>
                        <textarea id="body_markdown" name="body_markdown" rows="14" required
                            class="block w-full border-0 bg-gray-900 px-4 py-3 font-mono text-sm text-gray-100 placeholder-gray-600 focus:ring-0"
                            placeholder="## What's new&#10;&#10;Describe the changes...">{{ old('body_markdown', $post->body_markdown) }}</textarea>
                    </div>
                    @error('body_markdown') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="rounded-xl border border-gray-100 bg-gray-50/70 p-5">
                    <h3 class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                        SEO Settings
                    </h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="seo_title" class="block text-sm font-medium text-gray-600">SEO Title</label>
                            <input type="text" id="seo_title" name="seo_title" value="{{ old('seo_title', $post->seo_title) }}"
                                class="mt-1.5 block w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                placeholder="Custom title for search engines">
                        </div>
                        <div>
                            <label for="seo_description" class="block text-sm font-medium text-gray-600">SEO Description</label>
                            <textarea id="seo_description" name="seo_description" rows="2"
                                class="mt-1.5 block w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                placeholder="Meta description for search results">{{ old('seo_description', $post->seo_description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.posts.index') }}"
                   class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 active:scale-[0.97]">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200 transition-all duration-200 hover:from-indigo-700 hover:to-indigo-800 hover:shadow-lg hover:shadow-indigo-200 active:scale-[0.97]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
