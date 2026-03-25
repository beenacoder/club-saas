{{-- <a {{ $attributes->merge(['class' => 'px-4 py-2 rounded text-white bg-blue-600 hover:bg-blue-700'])}}>
    {{ $slot }}
</a> --}}



@props([
    'variant' => 'primary',
])

@php
$base = 'px-4 py-2 rounded-lg text-sm font-medium transition';

$variants = [
    'primary' => 'bg-blue-600 text-white hover:bg-blue-700',
    'success' => 'bg-green-600 text-white hover:bg-green-700',
    'danger' => 'bg-red-600 text-white hover:bg-red-700',
    'secondary' => 'bg-gray-200 text-gray-700 hover:bg-gray-300',
];
@endphp

<button {{ $attributes->merge([
    'class' => $base . ' ' . $variants[$variant]
]) }}>
    {{ $slot }}
</button>
