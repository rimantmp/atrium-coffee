@props([
    'priority',
    'suffix' => null,
])

@php
    $variants = [
        1 => ['label' => 'P1 Khusus', 'class' => 'border-red-200 bg-red-50 text-red-800'],
        2 => ['label' => 'P2 Tinggi', 'class' => 'border-amber-200 bg-amber-50 text-amber-800'],
        3 => ['label' => 'P3 Normal', 'class' => 'border-stone-200 bg-stone-100 text-stone-700'],
    ];

    $variant = $variants[(int) $priority] ?? $variants[3];
    $text = $variant['label'] . ($suffix ? ' ' . $suffix : '');
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold ' . $variant['class']]) }}>
    {{ $text }}
</span>
