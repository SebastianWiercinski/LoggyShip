<x-layouts.setup :step="$step" :totalSteps="$totalSteps" :stepTitle="$stepTitle">
    <h2 class="text-lg font-semibold text-gray-900">Connect GitHub</h2>
    <p class="mt-1 text-sm text-gray-600">Enter a Personal Access Token so LoggyShip can read your repositories.</p>

    <div class="mt-4 rounded-lg bg-blue-50 border border-blue-200 px-4 py-3">
        <p class="text-xs text-blue-800">
            Create a token at <strong>GitHub &rarr; Settings &rarr; Developer settings &rarr; Personal access tokens &rarr; Fine-grained tokens</strong>.
            Grant read access to <em>Contents</em>, <em>Pull requests</em>, and <em>Metadata</em>.
        </p>
    </div>

    <form method="POST" action="{{ route('setup.github.store') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="github_pat" class="block text-sm font-medium text-gray-700">Personal Access Token</label>
            <input type="password" name="github_pat" id="github_pat" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"
                placeholder="ghp_xxxxxxxxxxxx or github_pat_xxxx">
            <p class="mt-1 text-xs text-gray-500">Stored encrypted. Never sent to the LLM.</p>
        </div>

        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
            Test Connection &amp; Continue
        </button>
    </form>
</x-layouts.setup>
