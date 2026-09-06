@extends('layouts.customer', ['title' => 'Menu Atrium Coffee'])
@section('content')
<section class="rounded-3xl bg-coffee-900 px-5 py-8 text-white sm:px-8 sm:py-10">
    <p class="text-sm font-semibold uppercase tracking-widest text-coffee-200">Hangat, nyaman, tanpa antre lama</p>
    <h1 class="mt-3 max-w-2xl text-3xl font-bold tracking-tight sm:text-4xl">Pesan menu favoritmu dari meja.</h1>
    <p class="mt-3 max-w-xl text-sm leading-6 text-coffee-100">Pilih menu, isi data singkat, lalu pantau antrean secara langsung.</p>
</section>

<form method="POST" action="{{ route('checkout') }}" class="mt-8">@csrf
    <input type="hidden" name="checkout_token" value="{{ old('checkout_token', session('checkout_token')) }}">
    <div class="grid gap-8 lg:grid-cols-[1fr_340px]">
        <div class="space-y-8">
            @forelse($categories as $category)
                <section><div class="mb-4 flex items-end justify-between"><div><p class="text-xs font-semibold uppercase tracking-wider text-coffee-600">Kategori</p><h2 class="text-xl font-semibold">{{ $category->name }}</h2></div></div>
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($category->menus as $menu)
                        <article data-menu-card class="rounded-2xl border border-stone-200 bg-white p-4 shadow-sm transition">
                            @if($menu->image_path)
                                <img src="{{ Storage::url($menu->image_path) }}" alt="Foto {{ $menu->name }}" class="aspect-[4/3] w-full rounded-xl object-cover">
                            @else
                                <div class="flex aspect-[4/3] items-center justify-center rounded-xl bg-gradient-to-br from-coffee-100 to-coffee-200 text-5xl">☕</div>
                            @endif
                            <div class="mt-4 flex items-start justify-between gap-3"><div><h3 class="font-semibold">{{ $menu->name }}</h3><p class="mt-1 line-clamp-2 text-sm text-stone-600">{{ $menu->description ?: 'Racikan pilihan Atrium Coffee.' }}</p></div>@unless($menu->is_available)<span class="rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-700">Habis</span>@endunless</div>
                            <div class="mt-4 flex items-center justify-between"><span class="font-semibold tabular-nums text-coffee-800">Rp{{ number_format($menu->price, 0, ',', '.') }}</span>
                                @if($menu->is_available)<label class="flex min-h-11 cursor-pointer items-center gap-2 rounded-xl bg-coffee-100 px-3 text-sm font-semibold text-coffee-800 hover:bg-coffee-200"><input data-menu-check type="checkbox" name="items[{{ $menu->id }}][menu_id]" value="{{ $menu->id }}" class="size-4"> Pilih</label>@else<button disabled class="min-h-11 rounded-xl bg-stone-100 px-3 text-sm font-semibold text-stone-400">Habis</button>@endif
                            </div>
                            @if($menu->is_available)<div class="mt-3 grid grid-cols-[80px_1fr] gap-2"><input data-item-field disabled type="number" name="items[{{ $menu->id }}][quantity]" value="1" min="1" max="20" aria-label="Jumlah {{ $menu->name }}" class="min-h-11 rounded-xl border border-stone-300 px-3"><input data-item-field disabled name="items[{{ $menu->id }}][notes]" placeholder="Catatan (opsional)" class="min-h-11 min-w-0 rounded-xl border border-stone-300 px-3 text-sm"></div>@endif
                        </article>
                    @endforeach
                    </div>
                </section>
            @empty
                <div class="rounded-2xl border border-dashed border-stone-300 bg-white p-10 text-center"><p class="text-lg font-semibold">Menu belum tersedia</p><p class="mt-2 text-sm text-stone-600">Silakan hubungi staf Atrium Coffee.</p></div>
            @endforelse
        </div>
        <aside class="h-fit rounded-2xl border border-stone-200 bg-white p-5 shadow-sm lg:sticky lg:top-24">
            <h2 class="text-lg font-semibold">Data Pemesanan</h2><p class="mt-1 text-sm text-stone-600">Total akan dihitung ulang oleh sistem.</p>
            <div class="mt-5 space-y-4">
                <div><label class="mb-1.5 block text-sm font-medium" for="customer_name">Nama pelanggan</label><input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required maxlength="100" class="min-h-11 w-full rounded-xl border border-stone-300 px-3"></div>
                <div><label class="mb-1.5 block text-sm font-medium" for="customer_phone">Nomor telepon <span class="font-normal text-stone-500">(opsional)</span></label><input id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" class="min-h-11 w-full rounded-xl border border-stone-300 px-3"></div>
                <div><label class="mb-1.5 block text-sm font-medium" for="order_type">Jenis pesanan</label><select id="order_type" name="order_type" class="min-h-11 w-full rounded-xl border border-stone-300 px-3"><option value="DINE_IN">Dine-in</option><option value="TAKEAWAY">Takeaway</option></select></div>
                <div><label class="mb-1.5 block text-sm font-medium" for="table_number">Nomor meja</label><input id="table_number" name="table_number" value="{{ old('table_number') }}" placeholder="Contoh: T03" class="min-h-11 w-full rounded-xl border border-stone-300 px-3"></div>
                <div><label class="mb-1.5 block text-sm font-medium" for="notes">Catatan umum</label><textarea id="notes" name="notes" rows="3" class="w-full rounded-xl border border-stone-300 px-3 py-2.5">{{ old('notes') }}</textarea></div>
                <button class="min-h-12 w-full rounded-xl bg-coffee-600 px-4 font-semibold text-white shadow-sm hover:bg-coffee-700">Buat Pesanan</button>
            </div>
        </aside>
    </div>
</form>
@endsection
