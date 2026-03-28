<x-layouts.app title="Settings">
    <h1 class="text-2xl font-bold text-gray-900">Settings</h1>

    <div class="mt-6 space-y-6">
        {{-- LLM Settings --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">LLM Provider</h2>
            <form method="POST" action="{{ route('admin.settings.llm') }}" class="mt-4 space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Provider</label>
                        <select name="provider" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="anthropic" {{ $llmProvider === 'anthropic' ? 'selected' : '' }}>Anthropic</option>
                            <option value="gemini" {{ $llmProvider === 'gemini' ? 'selected' : '' }}>Google Gemini</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Model</label>
                        <input type="text" name="model" value="{{ $llmModel }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">API Key {{ $hasApiKey ? '(leave empty to keep current)' : '' }}</label>
                    <input type="password" name="api_key" placeholder="{{ $hasApiKey ? 'Configured' : 'Enter API key' }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save LLM Settings</button>
            </form>
        </div>

        {{-- GitHub Settings --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">GitHub</h2>
            <form method="POST" action="{{ route('admin.settings.github') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Personal Access Token {{ $hasGithubPat ? '(leave empty to keep current)' : '' }}</label>
                    <input type="password" name="github_pat" placeholder="{{ $hasGithubPat ? 'Configured (' . $githubUsername . ')' : 'Enter GitHub PAT' }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save GitHub Settings</button>
            </form>
        </div>

        {{-- Site Settings --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Public Site</h2>
            <form method="POST" action="{{ route('admin.settings.site') }}" class="mt-4 space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="site_name" value="{{ $siteName }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Accent Color</label>
                        <input type="color" name="accent_color" value="{{ $accentColor }}"
                            class="mt-1 h-10 w-full rounded-lg border border-gray-300 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Language for Updates</label>
                    <select name="language" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach(['de' => 'Deutsch', 'en' => 'English', 'fr' => 'Français', 'es' => 'Español', 'it' => 'Italiano', 'nl' => 'Nederlands', 'pt' => 'Português'] as $code => $name)
                            <option value="{{ $code }}" {{ $language === $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">SEO Title</label>
                    <input type="text" name="seo_title" value="{{ $seoTitle }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">SEO Description</label>
                    <textarea name="seo_description" rows="2"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $seoDescription }}</textarea>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="auto_publish" value="1" {{ $autoPublish ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Auto-publish drafts</span>
                            <p class="text-xs text-gray-500">Skip the review step and publish AI-generated updates directly. Useful for vibe coding workflows.</p>
                        </div>
                    </label>
                </div>

                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save Site Settings</button>
            </form>
        </div>

        {{-- Rules --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Classification Rules</h2>
            <form method="POST" action="{{ route('admin.settings.rules') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Exclude Paths (one per line)</label>
                    <textarea name="exclude_paths" rows="4" placeholder="docs/&#10;tests/&#10;.github/"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono">{{ $excludePaths }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Exclude Labels (comma-separated)</label>
                    <input type="text" name="exclude_labels" value="{{ $excludeLabels }}" placeholder="internal, chore, ci"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Always Include Labels (comma-separated)</label>
                    <input type="text" name="include_labels" value="{{ $includeLabels }}" placeholder="user-facing, feature, bug"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save Rules</button>
            </form>
        </div>
    </div>
</x-layouts.app>
