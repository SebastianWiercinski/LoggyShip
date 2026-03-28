<x-layouts.setup :step="$step" :totalSteps="$totalSteps" :stepTitle="$stepTitle">
    <h2 class="text-lg font-semibold text-gray-900">Setup Complete!</h2>
    <p class="mt-2 text-sm text-gray-600">
        LoggyShip is ready. Once you finish, you'll be taken to the admin dashboard where you can
        sync your repository and start generating product updates.
    </p>

    @if($repo)
        <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
            <p class="text-sm text-gray-700">
                <span class="font-medium">Connected repository:</span> {{ $repo->full_name }}
            </p>
        </div>
    @endif

    <div class="mt-6 space-y-3 text-sm text-gray-600">
        <h3 class="font-medium text-gray-900">Next steps:</h3>
        <ol class="list-inside list-decimal space-y-1">
            <li>Sync your repository to import recent changes</li>
            <li>Review AI-generated draft updates</li>
            <li>Publish updates to your public changelog</li>
        </ol>
    </div>

    <form method="POST" action="{{ route('setup.finish.store') }}" class="mt-6">
        @csrf
        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
            Go to Dashboard
        </button>
    </form>
</x-layouts.setup>
