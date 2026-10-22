@props([
    'variant' => 'info',
    'title' => null,
])

@php
    $styles = [
        'success' => ['border-emerald-200 bg-emerald-50 text-emerald-900', 'bg-emerald-100 text-emerald-700', 'Berhasil', 'M12 4.5v15m7.5-7.5h-15'],
        'warning' => ['border-amber-200 bg-amber-50 text-amber-900', 'bg-amber-100 text-amber-700', 'Perhatian', 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z'],
        'error' => ['border-red-200 bg-red-50 text-red-900', 'bg-red-100 text-red-700', 'Periksa kembali', 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'],
        'info' => ['border-sky-200 bg-sky-50 text-sky-900', 'bg-sky-100 text-sky-700', 'Informasi', 'M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z'],
    ];

    [$box, $iconBox, $defaultTitle, $iconPath] = $styles[$variant] ?? $styles['info'];
    $role = in_array($variant, ['success', 'error'], true) ? 'alert' : 'status';
@endphp

<div {{ $attributes->merge(['class' => "mb-6 flex items-start gap-3 rounded-2xl border p-4 text-sm shadow-sm {$box}"]) }} role="{{ $role }}">
    <span class="grid size-7 shrink-0 place-items-center rounded-full {{ $iconBox }}" aria-hidden="true">
        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
        </svg>
    </span>
    <div>
        <p class="font-semibold">{{ $title ?? $defaultTitle }}</p>
        <div class="mt-0.5">{{ $slot }}</div>
    </div>
</div>
