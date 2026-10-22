@extends('layouts.dashboard', ['title' => 'Manajemen Menu'])

@section('content')
<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="text-sm font-medium text-stone-500">Katalog produk</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-stone-900">Manajemen Menu</h1>
        <p class="mt-1 text-sm text-stone-500">Kelola harga, kategori, dan ketersediaan menu.</p>
    </div>
    <x-button type="button" data-modal-open="create-menu" class="shrink-0">
        <x-icon name="plus" class="size-4" /> Tambah Menu
    </x-button>
</div>

<x-card class="mt-6" padding="none">
    <form method="GET" class="grid gap-3 border-b border-stone-200 p-4 sm:grid-cols-[minmax(200px,1fr)_190px_190px_auto] sm:p-5">
        <label>
            <span class="sr-only">Cari menu</span>
            <input name="search" value="{{ request('search') }}" placeholder="Cari nama atau SKU..." class="ui-input">
        </label>
        <label>
            <span class="sr-only">Filter kategori</span>
            <select name="category" class="ui-input">
                <option value="">Semua kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </label>
        <label>
            <span class="sr-only">Filter status</span>
            <select name="status" class="ui-input">
                <option value="">Semua status</option>
                <option value="available" @selected(request('status') === 'available')>Tersedia</option>
                <option value="unavailable" @selected(request('status') === 'unavailable')>Tidak tersedia</option>
            </select>
        </label>
        <x-button type="submit" variant="secondary">Terapkan</x-button>
    </form>

    <x-data-table :head="['Menu', 'Kategori', 'Harga', 'Persiapan', 'Tersedia', 'Aksi']">
        <x-slot:desktop>
            @forelse($menus as $menu)
                <tr class="transition hover:bg-stone-50">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($menu->image_path)
                                <img src="{{ Storage::url($menu->image_path) }}" alt="" class="size-11 shrink-0 rounded-xl object-cover">
                            @else
                                <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-coffee-50 text-coffee-700" aria-hidden="true"><x-icon name="coffee" /></span>
                            @endif
                            <div class="min-w-0">
                                <p class="font-semibold text-stone-900">{{ $menu->name }}</p>
                                <p class="text-xs text-stone-500">{{ $menu->sku }} · {{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-stone-600">{{ $menu->category->name }}</td>
                    <td class="whitespace-nowrap px-5 py-4 font-semibold tabular-nums text-stone-800">Rp{{ number_format($menu->price, 0, ',', '.') }}</td>
                    <td class="px-5 py-4 text-sm text-stone-600">{{ $menu->estimated_prep_minutes }} menit</td>
                    <td class="px-5 py-4">
                        <form method="POST" action="{{ route('admin.menus.availability', $menu) }}" data-quick-toggle>
                            @csrf
                            @method('PATCH')
                            <x-form.toggle name="is_available" :checked="$menu->is_available" :label="'Ketersediaan '.$menu->name" bare />
                        </form>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-1">
                            <x-button type="button" variant="text" size="sm" data-modal-open="edit-menu-{{ $menu->id }}" class="admin-icon-button" aria-label="Edit {{ $menu->name }}">
                                <x-icon name="pencil" class="size-4" />
                            </x-button>
                            <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" onsubmit="return confirm('Hapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="text" size="sm" class="admin-icon-button hover:bg-red-50 hover:text-red-600" aria-label="Hapus {{ $menu->name }}">
                                    <x-icon name="trash" class="size-4" />
                                </x-button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <x-empty-state icon="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" title="Menu tidak ditemukan" description="Ubah filter pencarian atau tambahkan menu baru." />
                    </td>
                </tr>
            @endforelse
        </x-slot:desktop>

        <x-slot:mobile>
            @forelse($menus as $menu)
                <li class="space-y-3 p-5">
                    <div class="flex items-start gap-3">
                        @if($menu->image_path)
                            <img src="{{ Storage::url($menu->image_path) }}" alt="" class="size-12 shrink-0 rounded-xl object-cover">
                        @else
                            <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-coffee-50 text-coffee-700" aria-hidden="true"><x-icon name="coffee" /></span>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-bold text-stone-900">{{ $menu->name }}</p>
                            <p class="truncate text-xs text-stone-500">{{ $menu->sku }} · {{ $menu->category->name }}</p>
                        </div>
                        <p class="shrink-0 font-semibold tabular-nums text-stone-900">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                    </div>
                    <dl class="flex items-center justify-between gap-4 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-stone-500">Persiapan</dt>
                            <dd class="font-semibold text-stone-900">{{ $menu->estimated_prep_minutes }} menit</dd>
                        </div>
                        <x-badge :variant="$menu->is_active ? 'success' : 'neutral'">{{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}</x-badge>
                    </dl>
                    <form method="POST" action="{{ route('admin.menus.availability', $menu) }}" data-quick-toggle>
                        @csrf
                        @method('PATCH')
                        <x-form.toggle name="is_available" :checked="$menu->is_available" label="Tersedia" description="Pelanggan dapat memesan menu ini." />
                    </form>
                    <div class="flex justify-end gap-1">
                        <x-button type="button" variant="text" size="sm" data-modal-open="edit-menu-{{ $menu->id }}" class="admin-icon-button" aria-label="Edit {{ $menu->name }}">
                            <x-icon name="pencil" class="size-4" />
                        </x-button>
                        <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" onsubmit="return confirm('Hapus menu ini?')">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="text" size="sm" class="admin-icon-button hover:bg-red-50 hover:text-red-600" aria-label="Hapus {{ $menu->name }}">
                                <x-icon name="trash" class="size-4" />
                            </x-button>
                        </form>
                    </div>
                </li>
            @empty
                <li>
                    <x-empty-state icon="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" title="Menu tidak ditemukan" description="Ubah filter pencarian atau tambahkan menu baru." />
                </li>
            @endforelse
        </x-slot:mobile>
    </x-data-table>

    @if($menus->hasPages())
        <div class="border-t border-stone-200 px-5 py-4">{{ $menus->links() }}</div>
    @endif
</x-card>

<x-modal name="create-menu" title="Tambah Menu" description="Tambahkan produk ke katalog." width="max-w-2xl">
    <form method="POST" action="{{ route('admin.menus.store') }}" enctype="multipart/form-data" class="grid gap-4 sm:grid-cols-2">
        @csrf
        <x-form.select name="category_id" label="Kategori" required>
            <option value="">Pilih kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </x-form.select>
        <x-form.input name="name" label="Nama menu" required maxlength="120" />
        <x-form.input name="price" label="Harga" type="number" min="0" required hint="Tanpa titik atau koma." />
        <x-form.input name="estimated_prep_minutes" label="Waktu persiapan" type="number" :value="5" min="1" max="120" required hint="Dalam menit." />
        <div class="sm:col-span-2">
            <x-form.textarea name="description" label="Deskripsi" :rows="3" />
        </div>
        <div class="sm:col-span-2">
            <label for="create-menu-image" class="mb-1.5 block text-sm font-semibold text-stone-800">Foto menu</label>
            <input id="create-menu-image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="ui-input py-2">
        </div>
        <x-form.toggle name="is_available" label="Tersedia" description="Pelanggan dapat memesan menu ini." :checked="true" />
        <x-form.toggle name="is_active" label="Aktif" description="Tampilkan menu di katalog." :checked="true" />
        <div class="flex justify-end gap-3 border-t border-stone-200 pt-4 sm:col-span-2">
            <x-button type="button" variant="secondary" data-modal-close>Batal</x-button>
            <x-button type="submit">Simpan Menu</x-button>
        </div>
    </form>
</x-modal>

@foreach($menus as $menu)
    <x-modal name="edit-menu-{{ $menu->id }}" title="Edit Menu" :description="'Perbarui '.$menu->name.'.'" width="max-w-2xl">
        <form method="POST" action="{{ route('admin.menus.update', $menu) }}" enctype="multipart/form-data" class="grid gap-4 sm:grid-cols-2">
            @csrf
            @method('PUT')
            <x-form.select name="category_id" label="Kategori" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected($menu->category_id === $category->id)>{{ $category->name }}</option>
                @endforeach
            </x-form.select>
            <x-form.input name="name" label="Nama menu" :value="$menu->name" required maxlength="120" />
            <x-form.input name="price" label="Harga" type="number" :value="$menu->price" min="0" required hint="Tanpa titik atau koma." />
            <x-form.input name="estimated_prep_minutes" label="Waktu persiapan" type="number" :value="$menu->estimated_prep_minutes" min="1" max="120" required hint="Dalam menit." />
            <div class="sm:col-span-2">
                <x-form.textarea name="description" label="Deskripsi" :value="$menu->description" :rows="3" />
            </div>
            <div class="sm:col-span-2">
                <label for="edit-menu-images-{{ $menu->id }}" class="mb-1.5 block text-sm font-semibold text-stone-800">Ganti foto</label>
                <input id="edit-menu-images-{{ $menu->id }}" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="ui-input py-2">
                <p class="mt-1.5 text-xs text-stone-500">Biarkan kosong untuk mempertahankan foto saat ini.</p>
            </div>
            <x-form.toggle name="is_available" label="Tersedia" description="Pelanggan dapat memesan menu ini." :checked="$menu->is_available" />
            <x-form.toggle name="is_active" label="Aktif" description="Tampilkan menu di katalog." :checked="$menu->is_active" />
            <div class="flex justify-end gap-3 border-t border-stone-200 pt-4 sm:col-span-2">
                <x-button type="button" variant="secondary" data-modal-close>Batal</x-button>
                <x-button type="submit">Simpan Perubahan</x-button>
            </div>
        </form>
    </x-modal>
@endforeach
@endsection
