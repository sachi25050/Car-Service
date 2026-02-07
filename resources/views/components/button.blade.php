@props(['type' => 'button', 'variant' => 'primary', 'size' => 'md', 'icon' => null])

@php
$classes = [
    'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500',
    'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-gray-500',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
    'warning' => 'bg-yellow-600 text-white hover:bg-yellow-700 focus:ring-yellow-500',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];
@endphp

<button type="{{ $type }}" 
        {{ $attributes->merge(['class' => "inline-flex items-center gap-2 font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200 {$classes[$variant]} {$sizes[$size]}"]) }}>
    @if($icon)
    <i class="bi bi-{{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>
