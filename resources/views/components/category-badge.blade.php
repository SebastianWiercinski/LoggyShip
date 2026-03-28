@props(['category'])

@php
$colors = [
    'new' => 'bg-emerald-100 text-emerald-800',
    'improved' => 'bg-blue-100 text-blue-800',
    'fixed' => 'bg-orange-100 text-orange-800',
    'performance' => 'bg-purple-100 text-purple-800',
    'security' => 'bg-red-100 text-red-800',
];
$color = $colors[$category] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $color }}">
    {{ ucfirst($category) }}
</span>
