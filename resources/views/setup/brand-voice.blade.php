<x-layouts.setup :step="$step" :totalSteps="$totalSteps" :stepTitle="$stepTitle">
    <h2 class="text-lg font-semibold text-gray-900">Brand Voice</h2>
    <p class="mt-1 text-sm text-gray-600">Help LoggyShip write updates that sound like your brand. You can refine this later.</p>

    <form method="POST" action="{{ route('setup.brand-voice.store') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Language for Updates</label>
            <select name="language" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="de" selected>Deutsch</option>
                <option value="en">English</option>
                <option value="fr">Fran&ccedil;ais</option>
                <option value="es">Espa&ntilde;ol</option>
                <option value="it">Italiano</option>
                <option value="nl">Nederlands</option>
                <option value="pt">Portugu&ecirc;s</option>
                <option value="pl">Polski</option>
                <option value="ja">Japanese</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Example Text (optional)</label>
            <textarea name="sample_text" rows="5" placeholder="Paste a text sample that represents your brand voice (e.g., from your website, blog, or about page)..."
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('sample_text') }}</textarea>
            <p class="mt-1 text-xs text-gray-500">This helps the AI match your tone and style.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">No-Go Words (optional)</label>
            <input type="text" name="no_go_words" placeholder="e.g., disruptive, leverage, synergy" value="{{ old('no_go_words') }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <p class="mt-1 text-xs text-gray-500">Comma-separated words the AI should avoid.</p>
        </div>

        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
            Continue
        </button>
    </form>
</x-layouts.setup>
