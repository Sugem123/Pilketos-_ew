@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
])

@php
    $variants = [
        'primary' => 'bg-accent text-secondary hover:bg-gray-800',
        'success' => 'bg-green-600 text-white hover:bg-green-700',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'secondary' => 'bg-gray-100 text-gray-600 hover:bg-gray-200',
        'ghost' => 'p-2 rounded-lg transition-colors hover:bg-gray-100',
    ];

    $sizes = [
        'sm' => 'px-4 py-2 text-sm gap-1.5',
        'md' => 'px-5 py-2.5 gap-2',
        'lg' => 'px-6 py-3 gap-2.5',
    ];
@endphp

<button {{ $attributes->merge([
    'type' => $type,
    'class' => 'inline-flex items-center justify-center font-medium rounded-xl transition-colors cursor-pointer select-none ' . $variants[$variant] . ' ' . $sizes[$size],
]) }}>
    @if ($icon)
        <i class="{{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>