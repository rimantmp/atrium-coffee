@props([
    'name',
    'value' => '1',
    'checked' => false,
    'label' => null,
    'description' => null,
    'bare' => false,
])

{{--
    Sakelar berbasis checkbox asli, jadi tetap bisa dijangkau keyboard dan
    diumumkan pembaca layar. Jangan pasang tabindex="-1" pada input-nya.
    `bare` menyembunyikan label visual untuk dipakai di dalam sel tabel;
    label aksesibilitas tetap diambil dari prop `label`.
--}}
@php
    $wrapper = $bare
        ? 'inline-flex cursor-pointer items-center'
        : 'flex min-h-11 cursor-pointer items-center justify-between gap-4 rounded-xl border border-stone-200 p-3 transition duration-150 ease-out hover:border-stone-300';
@endphp

<label {{ $attributes->merge(['class' => $wrapper . ' has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-coffee-500 has-[:focus-visible]:ring-offset-2']) }}>
    @if($label)
        <span @class(['sr-only' => $bare])>
            <span @class(['block text-sm font-semibold text-stone-800' => ! $bare])>{{ $label }}</span>
            @if($description && ! $bare)
                <span class="mt-0.5 block text-xs text-stone-500">{{ $description }}</span>
            @endif
        </span>
    @endif

    <span class="relative inline-flex h-6 w-11 shrink-0 items-center">
        <input
            type="checkbox"
            name="{{ $name }}"
            value="{{ $value }}"
            @checked($checked)
            class="peer sr-only"
        >
        <span class="block h-6 w-11 rounded-full bg-stone-300 transition peer-checked:bg-coffee-600 peer-focus-visible:ring-2 peer-focus-visible:ring-coffee-500 peer-focus-visible:ring-offset-2"></span>
        <span class="pointer-events-none absolute left-0.5 top-0.5 size-5 rounded-full bg-white shadow-sm transition peer-checked:translate-x-5"></span>
    </span>
</label>
