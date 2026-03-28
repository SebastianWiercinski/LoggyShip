<x-layouts.setup :step="$step" :totalSteps="$totalSteps" :stepTitle="$stepTitle">
    <h2 class="text-lg font-semibold text-gray-900">System Check</h2>
    <p class="mt-1 text-sm text-gray-600">Checking your server meets the requirements.</p>

    <div class="mt-6 space-y-3">
        @foreach($checks as $check)
            <div class="flex items-center justify-between rounded-lg border px-4 py-3 {{ $check['passed'] ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                <div>
                    <span class="text-sm font-medium {{ $check['passed'] ? 'text-green-800' : 'text-red-800' }}">{{ $check['name'] }}</span>
                    <span class="ml-2 text-xs text-gray-500">({{ $check['current'] }})</span>
                </div>
                <span class="text-sm {{ $check['passed'] ? 'text-green-600' : 'text-red-600' }}">
                    {{ $check['passed'] ? 'Pass' : 'Required: ' . $check['required'] }}
                </span>
            </div>
        @endforeach
    </div>

    <form method="POST" action="{{ route('setup.system-check.store') }}" class="mt-6">
        @csrf
        <button type="submit" {{ !$allPassed ? 'disabled' : '' }}
            class="w-full rounded-lg px-4 py-2.5 text-sm font-medium text-white transition-colors {{ $allPassed ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-400 cursor-not-allowed' }}">
            Continue
        </button>
    </form>
</x-layouts.setup>
