<x-layouts.setup :step="$step" :totalSteps="$totalSteps" :stepTitle="$stepTitle">
    <h2 class="text-lg font-semibold text-gray-900">Select Repository</h2>
    <p class="mt-1 text-sm text-gray-600">Choose which repository to track for product updates.</p>

    <form method="POST" action="{{ route('setup.repository.store') }}" class="mt-6 space-y-4">
        @csrf

        @if(count($repos) > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700">Repository</label>
                <select name="repository" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @foreach($repos as $repo)
                        <option value="{{ $repo['full_name'] }}">{{ $repo['full_name'] }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <div class="rounded-lg bg-yellow-50 border border-yellow-200 px-4 py-3 text-sm text-yellow-800">
                No repositories found. You can enter a repository name manually.
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Repository (owner/name)</label>
                <input type="text" name="repository" placeholder="owner/repo-name" required
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
        @endif

        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
            Continue
        </button>
    </form>
</x-layouts.setup>
