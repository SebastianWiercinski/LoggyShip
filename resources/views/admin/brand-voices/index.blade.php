<x-layouts.app title="Brand Voices">
    <h1 class="text-2xl font-bold text-gray-900">Brand Voices</h1>

    @if($voices->isEmpty())
        <div class="mt-8 rounded-xl border border-gray-200 bg-white p-8 text-center">
            <p class="text-gray-500">No brand voices configured.</p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach($voices as $voice)
                <div class="rounded-xl border border-gray-200 bg-white p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-medium text-gray-900">{{ $voice->name }}</h3>
                                @if($voice->is_active)
                                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Active</span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Language: {{ strtoupper($voice->language) }}</p>

                            @if($voice->generated_profile)
                                <p class="mt-2 text-sm text-gray-600">{{ $voice->generated_profile['voice_summary'] ?? 'Profile generated' }}</p>
                            @else
                                <p class="mt-2 text-sm text-yellow-600">Profile not yet analyzed</p>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.brand-voices.edit', $voice) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Edit</a>
                            @if(!$voice->is_active)
                                <form method="POST" action="{{ route('admin.brand-voices.activate', $voice) }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-green-600 hover:text-green-800">Activate</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.brand-voices.analyze', $voice) }}">
                                @csrf
                                <button type="submit" class="text-sm text-purple-600 hover:text-purple-800">Analyze</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.app>
