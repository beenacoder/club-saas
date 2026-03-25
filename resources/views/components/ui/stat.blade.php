@props([
    'title',
    'value',
    'color' => 'gray'
])

@php
$colors = [
    'gray' => 'text-gray-700',
    'green' => 'text-green-600',
    'red' => 'text-red-600',
    'blue' => 'text-blue-600',
];
@endphp

<x-ui.card>
    <div class="flex flex-col">
        <span class="text-sm text-gray-500">{{ $title }}</span>

        <span class="text-2xl font-bold {{ $colors[$color] }}">
            {{ $value }}
        </span>
    </div>
</x-ui.card>
