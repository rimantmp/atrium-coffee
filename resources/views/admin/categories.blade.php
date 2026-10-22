@extends('layouts.dashboard', ['title' => 'Manajemen Kategori'])

@section('content')
<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="text-sm font-medium text-stone-500">Struktur katalog</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-stone-900">Manajemen Kategori</h1>
        <p class="mt-1 text-sm text-stone-500">Atur pengelompokan dan urutan menu.</p>
    </div>
    <x-button type="button" data-modal-open="create-category" class="shrink-0">
        <x-icon name="plus" class="size-4" /> Tambah Kategori
    </x-button>
</div>

<x-card class="mt-6" padding="none">
    <form method="GET" class="flex flex-col gap-3 border-b border-stone-200 p-4 sm:flex-row sm:p-5">
        <label class="max-w-xl flex-1">
            <span class="sr-only">Cari kategori</span>
            <input name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="ui-input">
        </label>
        <x-button type="submit" variant="secondary">Cari</x-button>
        @if(request('search'))
            <x-button :href="route('admin.categories.index')" variant="secondary">Reset</x-button>
        @endif
    </form>

    <x-data-table :head="['Kategori', 'Jumlah Menu', 'Urutan', 'Status', 'Aksi']">
        <x-slot:desktop>
            @forelse($categories as $category)
                <tr class="transition hover:bg-stone-50">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-stone-900">{{ $category->name }}</p>
                        <p class="text-sm text-stone-500">{{ $category->slug }}</p>
                    </td>
                    <td class="px-5 py-4 text-sm font-semibold text-stone-700">{{ $category->menus_count }} menu</td>
                    <td class="px-5 py-4 text-sm tabular-nums text-stone-600">{{ $category->sort_order }}</td>
                    <td class="px-5 py-4">
                        <x-badge :variant="$category->is_active ? 'success' : 'neutral'">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</x-badge>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-1">
                            <x-button type="button" variant="text" size="sm" data-modal-open="edit-category-{{ $category->id }}" class="admin-icon-button" aria-label="Edit {{ $category->name }}">
                                <x-icon name="pencil" class="size-4" />
                            </x-button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="text" size="sm" class="admin-icon-button hover:bg-red-50 hover:text-red-600" aria-label="Hapus {{ $category->name }}">
                                    <x-icon name="trash" class="size-4" />
                                </x-button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <x-empty-state icon="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" title="Kategori tidak ditemukan" description="Ubah kata kunci pencarian atau tambahkan kategori baru." />
                    </td>
                </tr>
            @endforelse
        </x-slot:desktop>

        <x-slot:mobile>
            @forelse($categories as $category)
                <li class="space-y-3 p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate font-bold text-stone-900">{{ $category->name }}</p>
                            <p class="truncate text-sm text-stone-500">{{ $category->slug }}</p>
                        </div>
                        <x-badge :variant="$category->is_active ? 'success' : 'neutral'">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</x-badge>
                    </div>
                    <dl class="flex items-center justify-between gap-4 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-stone-500">Jumlah menu</dt>
                            <dd class="font-semibold text-stone-900">{{ $category->menus_count }} menu</dd>
                        </div>
                        <div class="text-right">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-stone-500">Urutan</dt>
                            <dd class="font-semibold tabular-nums text-stone-900">{{ $category->sort_order }}</dd>
                        </div>
                    </dl>
                    <div class="flex justify-end gap-1">
                        <x-button type="button" variant="text" size="sm" data-modal-open="edit-category-{{ $category->id }}" class="admin-icon-button" aria-label="Edit {{ $category->name }}">
                            <x-icon name="pencil" class="size-4" />
                        </x-button>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="text" size="sm" class="admin-icon-button hover:bg-red-50 hover:text-red-600" aria-label="Hapus {{ $category->name }}">
                                <x-icon name="trash" class="size-4" />
                            </x-button>
                        </form>
                    </div>
                </li>
            @empty
                <li>
                    <x-empty-state icon="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" title="Kategori tidak ditemukan" description="Ubah kata kunci pencarian atau tambahkan kategori baru." />
                </li>
            @endforelse
        </x-slot:mobile>
    </x-data-table>

    @if($categories->hasPages())
        <div class="border-t border-stone-200 px-5 py-4">{{ $categories->links() }}</div>
    @endif
</x-card>

<x-modal name="create-category" title="Tambah Kategori" description="Buat kelompok menu baru.">
    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
        @csrf
        <x-form.input name="name" label="Nama kategori" required maxlength="100" />
        <x-form.input name="sort_order" label="Urutan" type="number" :value="old('sort_order', 0)" min="0" max="999" required hint="Angka kecil tampil lebih dulu di menu publik." />
        <x-form.toggle name="is_active" label="Status aktif" description="Tampilkan kategori di menu publik." :checked="true" />
        <div class="flex justify-end gap-3 border-t border-stone-200 pt-4">
            <x-button type="button" variant="secondary" data-modal-close>Batal</x-button>
            <x-button type="submit">Simpan Kategori</x-button>
        </div>
    </form>
</x-modal>

@foreach($categories as $category)
    <x-modal name="edit-category-{{ $category->id }}" title="Edit Kategori" :description="'Perbarui '.$category->name.'.'">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <x-form.input name="name" label="Nama kategori" :value="$category->name" required maxlength="100" />
            <x-form.input name="sort_order" label="Urutan" type="number" :value="$category->sort_order" min="0" max="999" required hint="Angka kecil tampil lebih dulu di menu publik." />
            <x-form.toggle name="is_active" label="Status aktif" description="Tampilkan kategori di menu publik." :checked="$category->is_active" />
            <div class="flex justify-end gap-3 border-t border-stone-200 pt-4">
                <x-button type="button" variant="secondary" data-modal-close>Batal</x-button>
                <x-button type="submit">Simpan Perubahan</x-button>
            </div>
        </form>
    </x-modal>
@endforeach
@endsection
