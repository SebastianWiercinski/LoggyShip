<x-layouts.app title="Settings">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.4s ease-out both; }
        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
            border-radius: 12px;
            background: #d1d5db;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
            transition: transform 0.2s ease;
        }
        .toggle-input:checked + .toggle-switch {
            background: #6366f1;
        }
        .toggle-input:checked + .toggle-switch::after {
            transform: translateX(20px);
        }
    </style>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="mt-1 text-sm text-gray-500">Configure your LoggyShip instance.</p>
        </div>
    </div>

    <div class="mt-8 space-y-8">
        {{-- LLM Settings --}}
        <div class="animate-fade-in rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-gray-700 to-gray-900 shadow-sm">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456Z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">LLM Provider</h2>
                        <p class="mt-0.5 text-sm text-gray-500">Configure the AI model used to generate changelog content.</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.settings.llm') }}" class="p-6">
                @csrf
                <div class="space-y-5">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Provider</label>
                            <select name="provider"
                                class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                                <option value="anthropic" {{ $llmProvider === 'anthropic' ? 'selected' : '' }}>Anthropic</option>
                                <option value="gemini" {{ $llmProvider === 'gemini' ? 'selected' : '' }}>Google Gemini</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Model</label>
                            <input type="text" name="model" value="{{ $llmModel }}"
                                class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">API Key {{ $hasApiKey ? '(leave empty to keep current)' : '' }}</label>
                        <div class="relative mt-1.5">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/></svg>
                            </div>
                            <input type="password" name="api_key" placeholder="{{ $hasApiKey ? 'Configured' : 'Enter API key' }}"
                                class="block w-full rounded-xl border-gray-300 bg-gray-50/50 py-2.5 pl-11 pr-4 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end border-t border-gray-100 pt-5">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200 transition-all duration-200 hover:from-indigo-700 hover:to-indigo-800 hover:shadow-lg hover:shadow-indigo-200 active:scale-[0.97]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        Save LLM Settings
                    </button>
                </div>
            </form>
        </div>

        {{-- GitHub Settings --}}
        <div class="animate-fade-in rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-gray-800 to-gray-900 shadow-sm">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">GitHub</h2>
                        <p class="mt-0.5 text-sm text-gray-500">Connect your GitHub account for repository syncing.</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.settings.github') }}" class="p-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Personal Access Token {{ $hasGithubPat ? '(leave empty to keep current)' : '' }}</label>
                    <div class="relative mt-1.5">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/></svg>
                        </div>
                        <input type="password" name="github_pat" placeholder="{{ $hasGithubPat ? 'Configured (' . $githubUsername . ')' : 'Enter GitHub PAT' }}"
                            class="block w-full rounded-xl border-gray-300 bg-gray-50/50 py-2.5 pl-11 pr-4 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    @if($hasGithubPat)
                        <p class="mt-2 flex items-center gap-1.5 text-xs text-green-600">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                            Connected as {{ $githubUsername }}
                        </p>
                    @endif
                </div>
                <div class="mt-6 flex justify-end border-t border-gray-100 pt-5">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200 transition-all duration-200 hover:from-indigo-700 hover:to-indigo-800 hover:shadow-lg hover:shadow-indigo-200 active:scale-[0.97]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        Save GitHub Settings
                    </button>
                </div>
            </form>
        </div>

        {{-- Site Settings --}}
        <div class="animate-fade-in rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-sm shadow-emerald-200">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Public Site</h2>
                        <p class="mt-0.5 text-sm text-gray-500">Customize the appearance and behavior of your public changelog.</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.settings.site') }}" class="p-6">
                @csrf
                <div class="space-y-5">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Product Name</label>
                            <input type="text" name="site_name" value="{{ $siteName }}" required
                                class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Accent Color</label>
                            <div class="mt-1.5 flex items-center gap-3">
                                <input type="color" name="accent_color" value="{{ $accentColor }}"
                                    class="h-10 w-14 cursor-pointer rounded-lg border border-gray-300 p-0.5 shadow-sm">
                                <div class="flex-1 rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-2.5 text-sm font-mono text-gray-600">{{ $accentColor }}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Language for Updates</label>
                        <select name="language"
                            class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                            @foreach(['de' => 'Deutsch', 'en' => 'English', 'fr' => 'Fran&ccedil;ais', 'es' => 'Espa&ntilde;ol', 'it' => 'Italiano', 'nl' => 'Nederlands', 'pt' => 'Portugu&ecirc;s'] as $code => $name)
                                <option value="{{ $code }}" {{ $language === $code ? 'selected' : '' }}>{!! $name !!}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">SEO Title</label>
                            <input type="text" name="seo_title" value="{{ $seoTitle }}"
                                class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">SEO Description</label>
                            <textarea name="seo_description" rows="1"
                                class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">{{ $seoDescription }}</textarea>
                        </div>
                    </div>

                    {{-- Auto-publish toggle --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50/70 p-5">
                        <label class="flex cursor-pointer items-center justify-between gap-4">
                            <div>
                                <span class="text-sm font-semibold text-gray-700">Auto-publish drafts</span>
                                <p class="mt-0.5 text-xs text-gray-500">Skip the review step and publish AI-generated updates directly. Useful for vibe coding workflows.</p>
                            </div>
                            <div class="shrink-0">
                                <input type="hidden" name="auto_publish" value="0">
                                <input type="checkbox" name="auto_publish" value="1" {{ $autoPublish ? 'checked' : '' }} class="toggle-input peer sr-only">
                                <div class="toggle-switch"></div>
                            </div>
                        </label>
                    </div>

                    {{-- Publish Frequency --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50/70 p-5">
                        <div class="mb-4">
                            <h3 class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                Publish Frequency
                            </h3>
                            <p class="mt-0.5 text-xs text-gray-500">How often should new changelog entries be generated and published from synced source items?</p>
                        </div>
                        <div class="space-y-2.5">
                            @php
                                $frequencies = [
                                    'realtime' => ['label' => 'Every push', 'desc' => 'Generate a changelog entry immediately when new commits or PRs are synced.'],
                                    'daily' => ['label' => 'Daily', 'desc' => 'Batch all changes and publish one summary per day.'],
                                    'every_3_days' => ['label' => 'Every 3 days', 'desc' => 'Group changes over 3 days into a single update.'],
                                    'weekly' => ['label' => 'Weekly', 'desc' => 'Publish a weekly digest of all changes every Monday.'],
                                    'biweekly' => ['label' => 'Bi-weekly', 'desc' => 'Consolidate two weeks of changes into one comprehensive update.'],
                                    'monthly' => ['label' => 'Monthly', 'desc' => 'Publish a monthly changelog roundup on the 1st of each month.'],
                                ];
                                $currentFrequency = $publishFrequency ?? 'realtime';
                            @endphp
                            @foreach($frequencies as $value => $freq)
                                <label class="group flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 bg-white p-3.5 transition-all duration-200 hover:border-indigo-300 hover:bg-indigo-50/30 has-[:checked]:border-indigo-400 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-400/30">
                                    <input type="radio" name="publish_frequency" value="{{ $value }}" {{ $currentFrequency === $value ? 'checked' : '' }}
                                        class="mt-0.5 h-4 w-4 shrink-0 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="text-sm font-medium text-gray-800">{{ $freq['label'] }}</span>
                                        <p class="mt-0.5 text-xs text-gray-500">{{ $freq['desc'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end border-t border-gray-100 pt-5">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200 transition-all duration-200 hover:from-indigo-700 hover:to-indigo-800 hover:shadow-lg hover:shadow-indigo-200 active:scale-[0.97]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        Save Site Settings
                    </button>
                </div>
            </form>
        </div>

        {{-- Classification Rules --}}
        <div class="animate-fade-in rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 shadow-sm shadow-orange-200">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Classification Rules</h2>
                        <p class="mt-0.5 text-sm text-gray-500">Control which source items are included or excluded from changelog generation.</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.settings.rules') }}" class="p-6">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Exclude Paths</label>
                        <p class="mt-0.5 text-xs text-gray-400">One path per line. Changes in these paths will be ignored.</p>
                        <div class="mt-1.5 overflow-hidden rounded-xl border border-gray-700 shadow-sm">
                            <div class="flex items-center gap-2 border-b border-gray-700 bg-gray-800 px-4 py-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-red-400/80"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-yellow-400/80"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-green-400/80"></span>
                                <span class="ml-2 text-xs text-gray-500">paths</span>
                            </div>
                            <textarea name="exclude_paths" rows="4" placeholder="docs/&#10;tests/&#10;.github/"
                                class="block w-full border-0 bg-gray-900 px-4 py-3 font-mono text-sm text-gray-100 placeholder-gray-600 focus:ring-0">{{ $excludePaths }}</textarea>
                        </div>
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Exclude Labels</label>
                            <p class="mt-0.5 text-xs text-gray-400">Comma-separated labels to ignore</p>
                            <input type="text" name="exclude_labels" value="{{ $excludeLabels }}" placeholder="internal, chore, ci"
                                class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Always Include Labels</label>
                            <p class="mt-0.5 text-xs text-gray-400">Comma-separated labels to always include</p>
                            <input type="text" name="include_labels" value="{{ $includeLabels }}" placeholder="user-facing, feature, bug"
                                class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end border-t border-gray-100 pt-5">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200 transition-all duration-200 hover:from-indigo-700 hover:to-indigo-800 hover:shadow-lg hover:shadow-indigo-200 active:scale-[0.97]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        Save Rules
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
