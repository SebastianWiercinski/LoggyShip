<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Setup' }} — LoggyShip</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
    <div class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="w-full max-w-lg">
            {{-- Logo --}}
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-indigo-600">LoggyShip</h1>
                <p class="mt-1 text-sm text-gray-500">Setup Wizard</p>
            </div>

            {{-- Progress indicator --}}
            @if(isset($step) && isset($totalSteps))
                <div class="mb-6">
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>Step {{ $step }} of {{ $totalSteps }}</span>
                        <span>{{ $stepTitle ?? '' }}</span>
                    </div>
                    <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-gray-200">
                        <div class="h-full rounded-full bg-indigo-600 transition-all duration-300" style="width: {{ ($step / $totalSteps) * 100 }}%"></div>
                    </div>
                </div>
            @endif

            {{-- Card --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                @if($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
