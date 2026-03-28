@props(['category'])

@php
$colors = [
    'new' => 'bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 ring-1 ring-emerald-200/60',
    'improved' => 'bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 ring-1 ring-blue-200/60',
    'fixed' => 'bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 ring-1 ring-amber-200/60',
    'performance' => 'bg-gradient-to-r from-violet-50 to-purple-50 text-violet-700 ring-1 ring-violet-200/60',
    'security' => 'bg-gradient-to-r from-rose-50 to-red-50 text-rose-700 ring-1 ring-rose-200/60',
];
$color = $colors[$category] ?? 'bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 ring-1 ring-gray-200/60';
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold tracking-wide {{ $color }} transition-shadow hover:shadow-sm">
    {{ ucfirst($category) }}
</span>
