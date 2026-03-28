<x-layouts.app title="Edit Brand Voice">
    <div class="mb-6">
        <a href="{{ route('admin.brand-voices.index') }}" class="group inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition-colors hover:text-gray-900">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            Back to Brand Voices
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Form --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-violet-600 shadow-sm shadow-purple-200">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-gray-900">Edit Brand Voice</h1>
                            <p class="mt-0.5 text-sm text-gray-500">Configure the voice profile and sample content.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.brand-voices.update', $brandVoice) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Name</label>
                                <input type="text" name="name" value="{{ old('name', $brandVoice->name) }}" required
                                    class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Language</label>
                                <select name="language"
                                    class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                                    @foreach(['de' => 'Deutsch', 'en' => 'English', 'fr' => 'Fran&ccedil;ais', 'es' => 'Espa&ntilde;ol', 'it' => 'Italiano', 'nl' => 'Nederlands', 'pt' => 'Portugu&ecirc;s', 'pl' => 'Polski', 'ja' => 'Japanese'] as $code => $name)
                                        <option value="{{ $code }}" {{ $brandVoice->language === $code ? 'selected' : '' }}>{!! $name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Sample Texts</label>
                            <p class="mt-0.5 text-xs text-gray-400">Paste examples of your brand's writing. Separate multiple samples with ---</p>
                            <div class="mt-1.5 overflow-hidden rounded-xl border border-gray-700 shadow-sm">
                                <div class="flex items-center gap-2 border-b border-gray-700 bg-gray-800 px-4 py-2">
                                    <span class="h-2.5 w-2.5 rounded-full bg-red-400/80"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-yellow-400/80"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-green-400/80"></span>
                                    <span class="ml-2 text-xs text-gray-500">samples</span>
                                </div>
                                <textarea name="sample_text" rows="10" placeholder="Paste brand text samples here...&#10;&#10;---&#10;&#10;Separate multiple samples with three dashes"
                                    class="block w-full border-0 bg-gray-900 px-4 py-3 font-mono text-sm text-gray-100 placeholder-gray-600 focus:ring-0">{{ old('sample_text', implode("\n---\n", $brandVoice->sample_texts ?? [])) }}</textarea>
                            </div>
                            @error('sample_text') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">No-Go Words</label>
                            <p class="mt-0.5 text-xs text-gray-400">Words or phrases to avoid in generated content (comma-separated)</p>
                            <input type="text" name="no_go_words" value="{{ old('no_go_words', $brandVoice->no_go_words) }}" placeholder="e.g. synergy, leverage, disrupt"
                                class="mt-1.5 block w-full rounded-xl border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm shadow-sm transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                            @error('no_go_words') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('admin.brand-voices.index') }}"
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
        </div>

        {{-- Generated Profile sidebar --}}
        <div class="lg:col-span-1">
            @if($brandVoice->generated_profile)
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="flex items-center gap-2 text-sm font-bold text-gray-900">
                            <svg class="h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456Z"/></svg>
                            Generated Profile
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="overflow-hidden rounded-xl border border-gray-700">
                            <div class="flex items-center gap-2 border-b border-gray-700 bg-gray-800 px-3 py-1.5">
                                <span class="h-2 w-2 rounded-full bg-red-400/80"></span>
                                <span class="h-2 w-2 rounded-full bg-yellow-400/80"></span>
                                <span class="h-2 w-2 rounded-full bg-green-400/80"></span>
                                <span class="ml-1.5 text-[10px] text-gray-500">JSON</span>
                            </div>
                            <pre class="max-h-[600px] overflow-auto bg-gray-900 p-4 text-xs leading-relaxed text-gray-300"><code>{{ json_encode($brandVoice->generated_profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/></svg>
                    </div>
                    <p class="mt-3 text-sm font-medium text-gray-700">No profile yet</p>
                    <p class="mt-1 text-xs text-gray-400">Add sample texts and run analysis to generate the voice profile.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
