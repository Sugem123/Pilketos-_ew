@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
])

@php
    $variants = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-500/20 hover:shadow-indigo-500/40 border border-indigo-500/30 active:scale-[0.98]',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-500/20 active:scale-[0.98]',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700 shadow-md shadow-rose-500/20 active:scale-[0.98]',
        'secondary' => 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 active:scale-[0.98]',
        'ghost' => 'p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors',
    ];

    $sizes = [
        'sm' => 'px-3.5 py-2 text-xs font-semibold gap-1.5',
        'md' => 'px-4 py-2.5 text-sm font-semibold gap-2',
        'lg' => 'px-6 py-3 text-base font-bold gap-2.5',
    ];
@endphp

<button {{ $attributes->merge([
    'type' => $type,
    'class' => 'inline-flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer select-none ' . $variants[$variant] . ' ' . $sizes[$size],
]) }}>
    @if ($icon)
        <i class="{{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>