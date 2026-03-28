<x-layouts.app title="Edit Draft">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .delay-1 { animation-delay: 0.1s; }
        .markdown-editor {
            background-color: #1e1e2e;
            color: #cdd6f4;
            caret-color: #89b4fa;
            font-family: 'JetBrains Mono', 'Fira Code', 'SF Mono', 'Cascadia Code', ui-monospace, monospace;
            line-height: 1.7;
            tab-size: 4;
        }
        .markdown-editor:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.3), 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        .markdown-editor::placeholder {
            color: #585b70;
        }
        .markdown-editor::-webkit-scrollbar {
            width: 8px;
        }
        .markdown-editor::-webkit-scrollbar-track {
            background: #1e1e2e;
            border-radius: 4px;
        }
        .markdown-editor::-webkit-scrollbar-thumb {
            background: #45475a;
            border-radius: 4px;
        }
        .markdown-editor::-webkit-scrollbar-thumb:hover {
            background: #585b70;
        }
        .form-input-styled {
            transition: all 0.2s ease;
        }
        .form-input-styled:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }
    </style>

    {{-- Back navigation --}}
    <div class="animate-fade-in mb-6">
        <a href="{{ route('admin.drafts.show', $draft) }}" class="group inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
            <svg class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            Back to Draft
        </a>
    </div>

    <div class="animate-fade-in rounded-2xl border border-gray-200/80 bg-white shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-900">Edit Draft</h1>
                <p class="text-xs text-gray-500">Modify the content and metadata for this changelog entry.</p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.drafts.update', $draft) }}" class="p-6 lg:p-8 space-y-6">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div>
                <label class="flex items-center gap-1.5 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                    Title
                </label>
                <input type="text" name="title" value="{{ old('title', $draft->title) }}" required
                    class="form-input-styled block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400"
                    placeholder="Enter a descriptive title...">
            </div>

            {{-- Teaser --}}
            <div>
                <label class="flex items-center gap-1.5 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                    Teaser
                </label>
                <input type="text" name="teaser" value="{{ old('teaser', $draft->teaser) }}" maxlength="500"
                    class="form-input-styled block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400"
                    placeholder="A brief summary shown in listings...">
                <p class="mt-1.5 text-xs text-gray-400">Max 500 characters. Displayed as a preview in the changelog.</p>
            </div>

            {{-- Category --}}
            <div>
                <label class="flex items-center gap-1.5 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                    Category
                </label>
                <select name="category" class="form-input-styled block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach(['new', 'improved', 'fixed', 'performance', 'security'] as $cat)
                        <option value="{{ $cat }}" {{ $draft->category === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Body Markdown --}}
            <div>
                <label class="flex items-center justify-between mb-2">
                    <span class="flex items-center gap-1.5 text-sm font-semibold text-gray-700">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>
                        Body (Markdown)
                    </span>
                    <span class="text-xs text-gray-400 font-mono">md</span>
                </label>
                <div class="rounded-xl overflow-hidden shadow-lg ring-1 ring-gray-900/10">
                    <div class="flex items-center gap-2 bg-[#181825] px-4 py-2.5 border-b border-[#313244]">
                        <span class="h-3 w-3 rounded-full bg-[#f38ba8]/80"></span>
                        <span class="h-3 w-3 rounded-full bg-[#fab387]/80"></span>
                        <span class="h-3 w-3 rounded-full bg-[#a6e3a1]/80"></span>
                        <span class="ml-3 text-xs text-[#6c7086] font-mono">body_markdown</span>
                    </div>
                    <textarea name="body_markdown" rows="16" required
                        class="markdown-editor block w-full border-0 px-5 py-4 text-sm resize-y min-h-[300px]"
                        placeholder="Write your changelog content in Markdown..."
                    >{{ old('body_markdown', $draft->body_markdown) }}</textarea>
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200/50 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-200/60 transition-all duration-200">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Save Changes
                </button>
                <a href="{{ route('admin.drafts.show', $draft) }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:shadow-md transition-all duration-200">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
