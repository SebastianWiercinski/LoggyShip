<x-layouts.app title="Edit Brand Voice">
    <div class="mb-4">
        <a href="{{ route('admin.brand-voices.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Back</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6">
        <h1 class="text-xl font-bold text-gray-900">Edit Brand Voice</h1>

        <form method="POST" action="{{ route('admin.brand-voices.update', $brandVoice) }}" class="mt-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $brandVoice->name) }}" required
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Language</label>
                <select name="language" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @foreach(['de' => 'Deutsch', 'en' => 'English', 'fr' => 'Français', 'es' => 'Español', 'it' => 'Italiano', 'nl' => 'Nederlands', 'pt' => 'Português', 'pl' => 'Polski', 'ja' => 'Japanese'] as $code => $name)
                        <option value="{{ $code }}" {{ $brandVoice->language === $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Sample Texts</label>
                <textarea name="sample_text" rows="8" placeholder="Paste brand text samples. Separate multiple samples with ---"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('sample_text', implode("\n---\n", $brandVoice->sample_texts ?? [])) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">No-Go Words</label>
                <input type="text" name="no_go_words" value="{{ old('no_go_words', $brandVoice->no_go_words) }}" placeholder="Comma-separated"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save</button>
                <a href="{{ route('admin.brand-voices.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>

    @if($brandVoice->generated_profile)
        <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Generated Profile</h2>
            <pre class="mt-4 overflow-x-auto rounded-lg bg-gray-50 p-4 text-xs text-gray-700">{{ json_encode($brandVoice->generated_profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    @endif
</x-layouts.app>
