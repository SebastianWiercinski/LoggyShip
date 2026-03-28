<x-layouts.setup :step="$step" :totalSteps="$totalSteps" :stepTitle="$stepTitle">
    <h2 class="text-lg font-semibold text-gray-900">LLM Provider</h2>
    <p class="mt-1 text-sm text-gray-600">LoggyShip uses AI to generate your product updates. Choose a provider and enter your API key.</p>

    <form method="POST" action="{{ route('setup.llm.store') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Provider</label>
            <select name="provider" id="llm-provider" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="anthropic">Anthropic (Claude)</option>
                <option value="gemini">Google Gemini</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Model</label>
            <input type="text" name="model" id="llm-model" value="claude-sonnet-4-20250514"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <p class="mt-1 text-xs text-gray-500" id="model-hint">Recommended: claude-sonnet-4-20250514. You can enter any model ID.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">API Key</label>
            <input type="password" name="api_key" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <p class="mt-1 text-xs text-gray-500">Your key is stored encrypted and never sent to the LLM.</p>
        </div>

        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
            Continue
        </button>
    </form>

    <script>
        const defaults = {
            anthropic: { model: 'claude-sonnet-4-20250514', hint: 'Recommended: claude-sonnet-4-20250514' },
            gemini: { model: 'gemini-2.5-flash', hint: 'Recommended: gemini-2.5-flash' },
        };
        document.getElementById('llm-provider').addEventListener('change', function() {
            const d = defaults[this.value];
            document.getElementById('llm-model').value = d.model;
            document.getElementById('model-hint').textContent = d.hint + '. You can enter any model ID.';
        });
    </script>
</x-layouts.setup>
