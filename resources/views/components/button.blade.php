@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
])

@php
    $variants = [
        'primary' => 'bg-coffee-600 text-white shadow-sm hover:bg-coffee-700 active:bg-coffee-800',
        'secondary' => 'border border-stone-300 bg-white text-stone-800 hover:bg-stone-50',
        'outline' => 'border border-coffee-600 bg-transparent text-coffee-700 hover:bg-coffee-50',
        'text' => 'text-coffee-700 hover:bg-coffee-50',
        'danger' => 'bg-red-600 text-white shadow-sm hover:bg-red-700 active:bg-red-800',
    ];

    $sizes = [
        'sm' => 'min-h-9 px-3 text-xs',
        'md' => 'min-h-11 px-4 text-sm',
        'lg' => 'min-h-12 px-6 text-base',
    ];
@endphp

@php($classes = 'inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition duration-150 ease-out disabled:cursor-not-allowed disabled:opacity-60 ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']))

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
