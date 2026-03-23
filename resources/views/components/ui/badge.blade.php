@props(['type' => 'default'])

@php
$colors = [
    'success' => 'bg-green-100 text-green-700',
    'danger' => 'bg-red-100 text-red-700',
    'warning' => 'bg-yellow-100 text-yellow-700',
];
@endphp

<span class="px-2 py-1 text-xs rounded {{ $colors[$type] ?? 'bg-gray-100' }}">
    {{ $slot }}
</span>
