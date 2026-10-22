@extends('layouts.customer', ['title' => 'Menu Atrium Coffee'])

@section('header-tools')
    <label class="relative hidden min-w-0 flex-1 md:block md:max-w-xl">
        <span class="sr-only">Cari menu</span>
        <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-stone-500" />
        <input data-menu-search type="search" placeholder="Cari menu favoritmu..." class="h-12 w-full rounded-xl border border-stone-200 bg-stone-50 pl-12 pr-4 text-sm text-stone-800 placeholder:text-stone-500 transition hover:border-stone-300 focus:border-coffee-500 focus:bg-white">
    </label>

    <a href="#pesanan" class="hidden items-center gap-3 lg:flex">
        <span class="relative text-stone-900">
            <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L20 8H7"></path><circle cx="10" cy="20" r="1"></circle><circle cx="18" cy="20" r="1"></circle></svg>
            <span data-cart-badge class="absolute -right-2 -top-2 grid size-5 place-items-center rounded-full bg-coffee-600 text-[10px] font-bold text-white">0</span>
        </span>
        <span class="text-left leading-tight"><span class="block text-xs text-stone-500">Keranjang</span><strong data-header-total class="text-sm text-stone-900">Rp0</strong></span>
        <x-icon name="arrow-right" class="size-4 rotate-90 text-stone-700" />
    </a>
@endsection

@section('content')
@php
    $menuSprites = [
        'Americano' => '0% 0%',
        'Cafe Latte' => '33.333% 0%',
        'Cappuccino' => '66.667% 0%',
        'Espresso' => '100% 0%',
        'Chocolate' => '0% 100%',
        'Matcha Latte' => '33.333% 100%',
        'Butter Croissant' => '66.667% 100%',
        'Chicken Sandwich' => '100% 100%',
    ];
    $availableMenuCount = $categories->sum(fn ($category) => $category->menus->where('is_available', true)->count());
@endphp

<section class="relative isolate overflow-hidden rounded-2xl bg-coffee-100 px-6 py-8 sm:px-8 lg:min-h-40 lg:px-9 lg:py-8">
    <div class="relative z-10 max-w-2xl">
        <p class="text-xs font-bold uppercase tracking-[0.3em] text-coffee-700">Atrium Coffee</p>
        <h1 class="mt-4 text-3xl font-extrabold tracking-[-0.035em] text-coffee-950 sm:text-4xl">Pesan favoritmu, tanpa antre lama.</h1>
        <p class="mt-2 text-sm text-coffee-800 sm:text-base">Pilih menu, atur pesanan, lalu pantau antrean secara langsung.</p>
    </div>
    <div class="absolute inset-y-0 right-0 hidden w-[48%] overflow-hidden lg:block" aria-hidden="true">
        <div class="absolute inset-0 z-10 bg-[linear-gradient(90deg,#f3eadf_0%,transparent_35%)]"></div>
        <div class="h-full bg-[url('/images/atrium-menu-sprite.png')] bg-[length:400%_200%] bg-[position:33.333%_0%] bg-no-repeat"></div>
    </div>
</section>

<form method="POST" action="{{ route('checkout') }}" class="mt-4" data-order-form>
    @csrf
    <input type="hidden" name="checkout_token" value="{{ old('checkout_token', session('checkout_token')) }}">

    <div class="grid items-start gap-7 xl:grid-cols-[minmax(0,1fr)_390px]">
        <div class="min-w-0">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex gap-2 overflow-x-auto pb-1" role="group" aria-label="Filter kategori">
                    <button type="button" data-category-filter="all" class="category-filter is-active">Semua</button>
                    @foreach($categories as $category)
                        <button type="button" data-category-filter="{{ $category->slug }}" class="category-filter">{{ $category->name }}</button>
                    @endforeach
                </div>
                <p class="shrink-0 text-sm text-stone-500"><span data-menu-count>{{ $availableMenuCount }}</span> menu tersedia</p>
            </div>

            <label class="relative mt-3 block md:hidden">
                <span class="sr-only">Cari menu</span>
                <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-stone-500" />
                <input data-menu-search type="search" placeholder="Cari menu favoritmu..." class="ui-input pl-12">
            </label>

            <div class="mt-6 space-y-7">
                @forelse($categories as $category)
                    <section data-menu-category="{{ $category->slug }}">
                        <h2 class="mb-3 text-2xl font-bold tracking-tight text-stone-900">{{ $category->name }}</h2>
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-2 2xl:grid-cols-3">
                            @foreach($category->menus as $menu)
                                @php($spritePosition = $menuSprites[$menu->name] ?? '33.333% 0%')
                                <article data-menu-card data-menu-name="{{ Str::lower($menu->name.' '.$menu->description) }}" data-menu-id="{{ $menu->id }}" data-item-name="{{ $menu->name }}" data-item-price="{{ (int) $menu->price }}" data-item-image="{{ $menu->image_path ? Storage::url($menu->image_path) : '' }}" data-item-position="{{ $spritePosition }}" class="group flex min-w-0 flex-col rounded-2xl border border-stone-200 bg-white p-3 shadow-sm transition duration-150 ease-out hover:-translate-y-0.5 hover:border-coffee-300 hover:shadow-md">
                                    @if($menu->image_path)
                                        <img src="{{ Storage::url($menu->image_path) }}" alt="Foto {{ $menu->name }}" class="aspect-[16/10] w-full rounded-xl object-cover">
                                    @else
                                        <div role="img" aria-label="Foto {{ $menu->name }}" class="aspect-[16/10] w-full rounded-xl bg-[url('/images/atrium-menu-sprite.png')] bg-[length:400%_200%] bg-no-repeat" style="background-position: {{ $spritePosition }}"></div>
                                    @endif

                                    <div class="flex flex-1 flex-col px-1 pb-0.5 pt-3">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0"><h3 class="font-bold text-stone-900">{{ $menu->name }}</h3><p class="mt-0.5 line-clamp-2 min-h-10 text-sm leading-5 text-stone-500">{{ $menu->description ?: 'Racikan pilihan Atrium Coffee.' }}</p></div>
                                            @unless($menu->is_available)<x-badge variant="error">Habis</x-badge>@endunless
                                        </div>
                                        <p class="mt-1 text-base font-extrabold tabular-nums text-coffee-800">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>

                                        @if($menu->is_available)
                                            <div class="mt-2 flex gap-2">
                                                <div class="grid h-11 grid-cols-3 overflow-hidden rounded-xl border border-stone-200 bg-white">
                                                    <button type="button" data-quantity-decrease aria-label="Kurangi {{ $menu->name }}" class="grid w-9 place-items-center text-lg text-stone-700 hover:bg-stone-50">−</button>
                                                    <input data-item-field data-quantity-input disabled type="number" name="items[{{ $menu->id }}][quantity]" value="1" min="1" max="20" aria-label="Jumlah {{ $menu->name }}" class="w-10 border-x border-stone-200 text-center text-sm tabular-nums [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                                                    <button type="button" data-quantity-increase aria-label="Tambah jumlah {{ $menu->name }}" class="grid w-9 place-items-center text-lg text-stone-700 hover:bg-stone-50">+</button>
                                                </div>
                                                <label class="grid min-h-11 min-w-20 flex-1 cursor-pointer place-items-center rounded-xl bg-coffee-100 px-3 text-sm font-semibold text-coffee-700 transition duration-150 ease-out hover:bg-coffee-200 has-[:checked]:bg-coffee-600 has-[:checked]:text-white">
                                                    <input data-menu-check type="checkbox" name="items[{{ $menu->id }}][menu_id]" value="{{ $menu->id }}" class="sr-only">
                                                    <span data-add-label>Tambah</span>
                                                </label>
                                            </div>
                                            <input data-item-field disabled type="hidden" name="items[{{ $menu->id }}][notes]" value="">
                                        @else
                                            <button disabled class="mt-2 h-11 w-full rounded-xl bg-stone-100 text-sm font-semibold text-stone-600">Tidak tersedia</button>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @empty
                    <x-card padding="none">
                        <x-empty-state title="Menu belum tersedia" description="Silakan hubungi staf Atrium Coffee." />
                    </x-card>
                @endforelse

                <div data-no-results hidden>
                    <x-card padding="none">
                        <x-empty-state icon="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" title="Menu tidak ditemukan" description="Coba kata kunci atau kategori lain." />
                    </x-card>
                </div>
            </div>
        </div>

        <aside id="pesanan" class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm xl:sticky xl:top-24">
            <div class="p-5 sm:p-6">
                <div class="flex items-center justify-between gap-4"><h2 class="text-2xl font-bold tracking-tight text-stone-900">Pesanan Anda</h2><x-badge variant="coffee"><span data-cart-count>0</span> item</x-badge></div>
                <div data-cart-empty class="mt-5 rounded-xl bg-stone-50 px-4 py-6 text-center text-sm text-stone-500">Belum ada menu di keranjang.</div>
                <div data-cart-list class="mt-4 space-y-3"></div>
                <div class="mt-5 flex items-center justify-between border-y border-stone-200 py-4"><span class="font-medium text-stone-900">Subtotal</span><strong data-cart-total class="text-2xl tracking-tight text-stone-900">Rp0</strong></div>

                <div class="pt-4">
                    <h3 class="text-xl font-bold text-stone-900">Data Pemesanan</h3>
                    <p class="mt-0.5 text-sm text-stone-500">Total akan dihitung ulang oleh sistem.</p>
                    <div class="mt-4 space-y-3.5">
                        <x-form.input name="customer_name" label="Nama pelanggan" :value="old('customer_name')" required maxlength="100" />
                        <x-form.input name="customer_phone" label="Nomor telepon" :value="old('customer_phone')" hint="Opsional. Diisi jika ingin dihubungi." />
                        <x-form.select name="order_type" label="Jenis pesanan">
                            <option value="DINE_IN" @selected(old('order_type') === 'DINE_IN')>Dine-in</option>
                            <option value="TAKEAWAY" @selected(old('order_type') === 'TAKEAWAY')>Takeaway</option>
                        </x-form.select>
                        <x-form.input name="table_number" label="Nomor meja" :value="old('table_number')" placeholder="Contoh: T03" />
                        <x-form.textarea name="notes" label="Catatan umum" :value="old('notes')" :rows="2" placeholder="Tulis catatan untuk pesanan..." />
                        <x-button type="submit" size="lg" class="w-full">
                            Buat Pesanan · <span data-button-total>Rp0</span>
                        </x-button>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</form>
@endsection

@section('mobile-cart-bar')
    <div data-mobile-cart-bar class="fixed inset-x-0 bottom-0 z-40 hidden border-t border-stone-200 bg-white p-3 shadow-soft md:hidden">
        <a href="#pesanan" class="flex min-h-12 items-center justify-between gap-4 rounded-xl bg-coffee-600 px-5 text-white">
            <span class="text-sm font-semibold"><span data-cart-count>0</span> item di keranjang</span>
            <span class="flex items-center gap-2 font-bold tabular-nums">
                <span data-cart-total>Rp0</span>
                <x-icon name="arrow-right" class="size-4" />
            </span>
        </a>
    </div>
@endsection
