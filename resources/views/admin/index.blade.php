@extends('layouts.dashboard', ['title' => 'Dashboard Admin'])

@section('content')
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach(['menus' => 'Total Menu', 'available' => 'Menu Tersedia', 'orders' => 'Order Hari Ini', 'revenue' => 'Omzet Hari Ini'] as $key => $label)
        <section class="ui-card relative overflow-hidden p-5">
            <span class="absolute -right-4 -top-4 size-20 rounded-full bg-coffee-50"></span>
            <p class="relative text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="relative mt-3 text-2xl font-bold tabular-nums text-slate-900">{{ $key === 'revenue' ? 'Rp'.number_format($stats[$key], 0, ',', '.') : $stats[$key] }}</p>
            <div class="relative mt-4 h-1.5 w-12 rounded-full bg-coffee-500"></div>
        </section>
    @endforeach
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-2">
    <section class="ui-card p-5 sm:p-6">
        <h2 class="text-lg font-semibold">Tambah Kategori</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
            @csrf
            <label class="text-sm font-medium">Nama<input name="name" value="{{ old('name') }}" required class="mt-1.5 min-h-11 w-full rounded-xl border border-stone-300 px-3"></label>
            <label class="text-sm font-medium">Urutan<input name="sort_order" value="{{ old('sort_order', 0) }}" type="number" min="0" max="999" required class="mt-1.5 min-h-11 w-full rounded-xl border border-stone-300 px-3"></label>
            <label class="flex items-center gap-2 text-sm font-medium"><input name="is_active" type="checkbox" value="1" checked class="size-4 rounded border-stone-300"> Aktif</label>
            <button class="min-h-11 rounded-xl bg-coffee-600 px-4 font-semibold text-white">Simpan Kategori</button>
        </form>
    </section>

    <section class="ui-card p-5 sm:p-6">
        <h2 class="text-lg font-semibold">Konfigurasi Scheduling</h2>
        <p class="mt-1 text-sm text-stone-500">Priority lebih kecil berarti lebih tinggi.</p>
        <form method="POST" action="{{ route('admin.settings') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
            @csrf
            @foreach(['default_priority' => 'Default Priority', 'minimum_priority' => 'Minimum Priority', 'aging_interval_minutes' => 'Aging Interval (menit)', 'overdue_minutes' => 'Overdue (menit)'] as $key => $label)
                <label class="text-sm font-medium">{{ $label }}<input name="{{ $key }}" type="number" min="1" value="{{ $settings[$key] ?? ($key === 'overdue_minutes' ? 30 : ($key === 'aging_interval_minutes' ? 10 : ($key === 'default_priority' ? 3 : 1))) }}" required class="mt-1.5 min-h-11 w-full rounded-xl border border-stone-300 px-3"></label>
            @endforeach
            <button class="min-h-11 rounded-xl bg-coffee-600 px-4 font-semibold text-white sm:col-span-2">Simpan Konfigurasi</button>
        </form>
    </section>
</div>

@include('admin.users', ['users' => $users])

<section class="ui-card mt-6 p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Manajemen Kategori</h2>
    <div class="mt-4 grid gap-3 md:grid-cols-2">
        @forelse($categories as $category)
            <details class="rounded-xl border border-stone-200 p-4">
                <summary class="cursor-pointer font-semibold">{{ $category->name }} <span class="text-sm font-normal text-stone-500">({{ $category->menus->count() }} menu)</span></summary>
                <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="mt-4 grid gap-3">
                    @csrf @method('PUT')
                    <input name="name" value="{{ $category->name }}" required class="min-h-11 rounded-xl border border-stone-300 px-3">
                    <input name="sort_order" value="{{ $category->sort_order }}" type="number" min="0" max="999" required class="min-h-11 rounded-xl border border-stone-300 px-3">
                    <label class="flex items-center gap-2 text-sm"><input name="is_active" type="checkbox" value="1" @checked($category->is_active) class="size-4 rounded border-stone-300"> Aktif</label>
                    <button class="min-h-11 rounded-xl bg-stone-800 px-4 text-sm font-semibold text-white">Perbarui</button>
                </form>
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="mt-3" onsubmit="return confirm('Hapus kategori ini?')">
                    @csrf @method('DELETE')
                    <button class="text-sm font-semibold text-red-700">Hapus kategori</button>
                </form>
            </details>
        @empty
            <p class="text-sm text-stone-500">Belum ada kategori.</p>
        @endforelse
    </div>
</section>

<section class="ui-card mt-6 p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Tambah Menu</h2>
    <form method="POST" action="{{ route('admin.menus.store') }}" enctype="multipart/form-data" class="mt-5 grid gap-4 sm:grid-cols-2">
        @csrf
        <select name="category_id" required class="min-h-11 rounded-xl border border-stone-300 px-3"><option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select>
        <input name="name" required placeholder="Nama menu" class="min-h-11 rounded-xl border border-stone-300 px-3">
        <input name="price" type="number" min="0" required placeholder="Harga" class="min-h-11 rounded-xl border border-stone-300 px-3">
        <input name="estimated_prep_minutes" type="number" min="1" max="120" value="5" required class="min-h-11 rounded-xl border border-stone-300 px-3">
        <textarea name="description" placeholder="Deskripsi" class="rounded-xl border border-stone-300 p-3 sm:col-span-2"></textarea>
        <label class="text-sm font-medium sm:col-span-2">Foto menu<input name="image" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1.5 block w-full rounded-xl border border-stone-300 p-2"></label>
        <label class="flex items-center gap-2 text-sm"><input name="is_available" type="checkbox" value="1" checked class="size-4 rounded border-stone-300"> Tersedia</label>
        <label class="flex items-center gap-2 text-sm"><input name="is_active" type="checkbox" value="1" checked class="size-4 rounded border-stone-300"> Aktif</label>
        <button class="min-h-11 rounded-xl bg-coffee-600 px-4 font-semibold text-white sm:col-span-2">Simpan Menu</button>
    </form>
</section>

<section class="ui-card mt-6 p-5 sm:p-6">
    <h2 class="text-lg font-semibold">Manajemen Menu</h2>
    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        @forelse($menus as $menu)
            <details class="rounded-xl border border-stone-200 p-4">
                <summary class="cursor-pointer font-semibold">{{ $menu->name }} <span class="text-sm font-normal text-stone-500">· {{ $menu->category->name }} · Rp{{ number_format($menu->price, 0, ',', '.') }}</span></summary>
                @if($menu->image_path)<img src="{{ Storage::url($menu->image_path) }}" alt="Foto {{ $menu->name }}" class="mt-4 h-32 w-full rounded-xl object-cover">@endif
                <form method="POST" action="{{ route('admin.menus.update', $menu) }}" enctype="multipart/form-data" class="mt-4 grid gap-3 sm:grid-cols-2">
                    @csrf @method('PUT')
                    <select name="category_id" required class="min-h-11 rounded-xl border border-stone-300 px-3">@foreach($categories as $category)<option value="{{ $category->id }}" @selected($menu->category_id === $category->id)>{{ $category->name }}</option>@endforeach</select>
                    <input name="name" value="{{ $menu->name }}" required class="min-h-11 rounded-xl border border-stone-300 px-3">
                    <input name="price" type="number" min="0" value="{{ $menu->price }}" required class="min-h-11 rounded-xl border border-stone-300 px-3">
                    <input name="estimated_prep_minutes" type="number" min="1" max="120" value="{{ $menu->estimated_prep_minutes }}" required class="min-h-11 rounded-xl border border-stone-300 px-3">
                    <textarea name="description" class="rounded-xl border border-stone-300 p-3 sm:col-span-2">{{ $menu->description }}</textarea>
                    <input name="image" type="file" accept="image/jpeg,image/png,image/webp" class="rounded-xl border border-stone-300 p-2 sm:col-span-2">
                    <label class="flex items-center gap-2 text-sm"><input name="is_available" type="checkbox" value="1" @checked($menu->is_available) class="size-4 rounded border-stone-300"> Tersedia</label>
                    <label class="flex items-center gap-2 text-sm"><input name="is_active" type="checkbox" value="1" @checked($menu->is_active) class="size-4 rounded border-stone-300"> Aktif</label>
                    <button class="min-h-11 rounded-xl bg-stone-800 px-4 text-sm font-semibold text-white sm:col-span-2">Perbarui Menu</button>
                </form>
                <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" class="mt-3" onsubmit="return confirm('Hapus menu ini?')">
                    @csrf @method('DELETE')
                    <button class="text-sm font-semibold text-red-700">Hapus menu</button>
                </form>
            </details>
        @empty
            <p class="text-sm text-stone-500">Belum ada menu.</p>
        @endforelse
    </div>
</section>
@endsection
