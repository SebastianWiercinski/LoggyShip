@props(['active' => false, 'href'])

<a href="{{ $href }}"
   {{ $attributes->class([
       'flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors',
       'bg-indigo-50 text-indigo-700' => $active,
       'text-gray-700 hover:bg-gray-100' => !$active,
   ]) }}>
    {{ $slot }}
</a>
