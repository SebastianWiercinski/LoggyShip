<x-layouts.setup :step="$step" :totalSteps="$totalSteps" :stepTitle="$stepTitle">
    <h2 class="text-lg font-semibold text-gray-900">Welcome to LoggyShip</h2>
    <p class="mt-2 text-sm text-gray-600">
        LoggyShip turns your GitHub activity into user-ready product updates.
        This wizard will help you set everything up in a few minutes.
    </p>

    <div class="mt-6 rounded-lg bg-gray-50 p-4">
        <h3 class="text-sm font-medium text-gray-700">Before you start, make sure you have:</h3>
        <ul class="mt-2 space-y-1 text-sm text-gray-600">
            <li>A GitHub Personal Access Token (with repo read access)</li>
            <li>An API key for Anthropic or Google Gemini</li>
            <li>A few minutes of your time</li>
        </ul>
    </div>

    <form method="POST" action="{{ route('setup.welcome.store') }}" class="mt-6">
        @csrf
        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
            Get Started
        </button>
    </form>
</x-layouts.setup>
