@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'hint' => null,
    'required' => false,
])

@php
    $id = $attributes->get('id') ?? 'field-' . str_replace(['[', ']', '.'], ['-', '', '-'], $name);
    $error = isset($errors) ? $errors->first($name) : null;
    $describedBy = collect([$hint ? $id . '-hint' : null, $error ? $id . '-error' : null])->filter()->implode(' ');
@endphp

<div>
    @if($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-semibold text-stone-800">
            {{ $label }}@if($required)<span class="text-red-600" aria-hidden="true"> *</span>@endif
        </label>
    @endif

    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @if($required) required aria-required="true" @endif
        @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if($error) aria-invalid="true" @endif
        {{ $attributes->except(['id'])->merge([
            'class' => 'ui-input' . ($error ? ' border-red-400 focus:border-red-500 focus:ring-red-500/20' : ''),
        ]) }}
    >

    @if($hint)
        <p id="{{ $id }}-hint" class="mt-1.5 text-xs text-stone-500">{{ $hint }}</p>
    @endif

    @if($error)
        <p id="{{ $id }}-error" class="mt-1.5 text-xs font-medium text-red-700">{{ $error }}</p>
    @endif
</div>
