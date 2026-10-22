@props([
    'status',
])

<x-badge :variant="$status->badgeVariant()" {{ $attributes }}>{{ $status->label() }}</x-badge>
