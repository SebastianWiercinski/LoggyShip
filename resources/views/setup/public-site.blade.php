<x-layouts.setup :step="$step" :totalSteps="$totalSteps" :stepTitle="$stepTitle">
    <h2 class="text-lg font-semibold text-gray-900">Public Site Settings</h2>
    <p class="mt-1 text-sm text-gray-600">Configure how your public changelog page looks.</p>

    <form method="POST" action="{{ route('setup.public-site.store') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Product Name</label>
            <input type="text" name="site_name" value="{{ old('site_name', 'My Product') }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Accent Color</label>
            <div class="mt-1 flex items-center gap-3">
                <input type="color" name="accent_color" id="accent-color" value="{{ old('accent_color', '#6366f1') }}"
                    class="h-10 w-14 cursor-pointer rounded-lg border border-gray-300">
                <span id="color-hex" class="text-sm text-gray-500 font-mono">#6366f1</span>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">SEO Title (optional)</label>
            <input type="text" name="seo_title" value="{{ old('seo_title') }}" placeholder="Product Updates"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">SEO Description (optional)</label>
            <textarea name="seo_description" rows="2" placeholder="Stay up to date with the latest improvements..."
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('seo_description') }}</textarea>
        </div>

        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
            Continue
        </button>
    </form>

    <script>
        document.getElementById('accent-color').addEventListener('input', function() {
            document.getElementById('color-hex').textContent = this.value;
        });
    </script>
</x-layouts.setup>
