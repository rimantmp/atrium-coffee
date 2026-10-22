@props([
    'variant' => 'neutral',
])

@php
    $variants = [
        'neutral' => 'bg-stone-100 text-stone-700 border-stone-200',
        'coffee' => 'bg-coffee-50 text-coffee-700 border-coffee-200',
        'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
        'error' => 'bg-red-50 text-red-800 border-red-200',
        'info' => 'bg-sky-50 text-sky-800 border-sky-200',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold ' . ($variants[$variant] ?? $variants['neutral'])]) }}>
    {{ $slot }}
</span>
