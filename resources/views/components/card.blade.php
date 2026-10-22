@props([
    'padding' => 'default',
    'as' => 'div',
])

@php
    $paddings = [
        'default' => 'p-5 sm:p-6',
        'compact' => 'p-4',
        'none' => '',
    ];
@endphp

<{{ $as }} {{ $attributes->merge(['class' => 'rounded-2xl border border-stone-200 bg-white shadow-sm ' . ($paddings[$padding] ?? $paddings['default'])]) }}>
    {{ $slot }}
</{{ $as }}>
