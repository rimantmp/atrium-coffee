@props([
    'name',
    'title' => null,
    'description' => null,
    'width' => 'max-w-xl',
])

<dialog data-modal="{{ $name }}" class="admin-modal {{ $width }}" aria-labelledby="{{ $name }}-title">
    <div class="admin-modal-panel">
        <div class="admin-modal-header">
            <div>
                <h2 id="{{ $name }}-title" class="text-base font-bold text-stone-900">{{ $title }}</h2>
                @if($description)
                    <p class="mt-1 text-sm text-stone-500">{{ $description }}</p>
                @endif
            </div>
            <button type="button" data-modal-close class="admin-icon-button" aria-label="Tutup dialog">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="px-5 py-5 sm:px-6">
            {{ $slot }}
        </div>
    </div>
</dialog>
