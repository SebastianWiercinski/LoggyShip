<x-layouts.setup :step="$step" :totalSteps="$totalSteps" :stepTitle="$stepTitle">
    <h2 class="text-lg font-semibold text-gray-900">Database Setup</h2>
    <p class="mt-1 text-sm text-gray-600">SQLite is recommended for easy setup. MySQL is available if needed.</p>

    <form method="POST" action="{{ route('setup.database.store') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Database Driver</label>
            <select name="driver" id="db-driver" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="sqlite" {{ $currentDriver === 'sqlite' ? 'selected' : '' }}>SQLite (recommended)</option>
                <option value="mysql">MySQL / MariaDB</option>
            </select>
        </div>

        <div id="mysql-fields" class="hidden space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Host</label>
                    <input type="text" name="host" value="127.0.0.1" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Port</label>
                    <input type="text" name="port" value="3306" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Database Name</label>
                <input type="text" name="database" value="loggyship" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
        </div>

        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
            Continue
        </button>
    </form>

    <script>
        document.getElementById('db-driver').addEventListener('change', function() {
            document.getElementById('mysql-fields').classList.toggle('hidden', this.value !== 'mysql');
        });
    </script>
</x-layouts.setup>
