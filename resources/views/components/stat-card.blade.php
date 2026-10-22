@props([
    'label',
    'value',
    'hint' => null,
    'tone' => 'coffee',
    'icon' => null,
])

@php
    $tones = [
        'coffee' => 'bg-coffee-50 text-coffee-700',
        'success' => 'bg-emerald-50 text-emerald-700',
        'warning' => 'bg-amber-50 text-amber-700',
        'info' => 'bg-sky-50 text-sky-700',
        'neutral' => 'bg-stone-100 text-stone-700',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-stone-200 bg-white p-5 shadow-sm']) }}>
    <div class="flex items-start justify-between gap-3">
        <p class="text-sm font-semibold text-stone-600">{{ $label }}</p>
        @if($icon)
            <span class="grid size-9 shrink-0 place-items-center rounded-xl {{ $tones[$tone] ?? $tones['coffee'] }}" aria-hidden="true">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $icon }}"/>
                </svg>
            </span>
        @endif
    </div>
    <p class="mt-3 text-3xl font-bold tabular-nums text-stone-900">{{ $value }}</p>
    @if($hint)
        <p class="mt-1 text-xs text-stone-500">{{ $hint }}</p>
    @endif
</div>
