@props(['category'])

@php
$colors = [
    'new' => 'bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 ring-1 ring-emerald-300/40 shadow-sm shadow-emerald-100/50',
    'improved' => 'bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 ring-1 ring-blue-300/40 shadow-sm shadow-blue-100/50',
    'fixed' => 'bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 ring-1 ring-amber-300/40 shadow-sm shadow-amber-100/50',
    'performance' => 'bg-gradient-to-r from-violet-50 to-purple-50 text-violet-700 ring-1 ring-violet-300/40 shadow-sm shadow-violet-100/50',
    'security' => 'bg-gradient-to-r from-rose-50 to-red-50 text-rose-700 ring-1 ring-rose-300/40 shadow-sm shadow-rose-100/50',
];
$dots = [
    'new' => 'bg-emerald-500',
    'improved' => 'bg-blue-500',
    'fixed' => 'bg-amber-500',
    'performance' => 'bg-violet-500',
    'security' => 'bg-rose-500',
];
$color = $colors[$category] ?? 'bg-gradient-to-r from-gray-50 to-slate-50 text-gray-600 ring-1 ring-gray-300/40 shadow-sm shadow-gray-100/50';
$dot = $dots[$category] ?? 'bg-gray-400';
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold tracking-wide {{ $color }} transition-all duration-200 hover:shadow-md">
    <span class="h-1.5 w-1.5 rounded-full {{ $dot }}"></span>
    {{ ucfirst($category) }}
</span>
