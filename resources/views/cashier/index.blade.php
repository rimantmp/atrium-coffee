@extends('layouts.dashboard', ['title' => 'Dashboard Kasir'])

@section('content')
<h1 class="sr-only">Dashboard Kasir</h1>

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-stat-card label="Pesanan Hari Ini" :value="$stats['today']" icon="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
    <x-stat-card label="Dalam Antrean" :value="$stats['waiting']" tone="info" icon="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
    <x-stat-card label="Siap Diambil" :value="$stats['ready']" tone="success" icon="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
    <x-stat-card label="Selesai Hari Ini" :value="$stats['completed']" tone="neutral" icon="m4.5 12.75 6 6 9-13.5" />
</div>

<div class="mt-6" x-data="{ open: false }">
    <x-card padding="none">
        <button type="button" @click="open = !open" :aria-expanded="open ? 'true' : 'false'" class="flex min-h-14 w-full items-center justify-between gap-4 px-5 text-left sm:px-6">
            <span>
                <span class="block text-lg font-bold text-stone-900">Buat Pesanan Walk-in</span>
                <span class="mt-0.5 block text-sm text-stone-500">Catat pesanan pelanggan di kasir, langsung masuk antrean setelah dibayar.</span>
            </span>
            <x-icon name="arrow-right" class="size-5 shrink-0 rotate-90 text-stone-500 transition duration-150 ease-out" ::class="open && 'rotate-[270deg]'" />
        </button>

        <form x-cloak x-show="open" method="POST" action="{{ route('cashier.orders.store') }}" class="grid gap-4 border-t border-stone-200 p-5 md:grid-cols-2 sm:p-6">
            @csrf
            <x-form.input name="customer_name" label="Nama pelanggan" required maxlength="100" />
            <x-form.input name="customer_phone" label="Nomor telepon" hint="Opsional." />
            <x-form.select name="order_type" label="Jenis pesanan">
                <option value="DINE_IN">Dine-in</option>
                <option value="TAKEAWAY">Takeaway</option>
            </x-form.select>
            <x-form.select name="method" label="Metode pembayaran">
                <option value="CASH">Tunai</option>
                <option value="QRIS">QRIS</option>
            </x-form.select>
            <x-form.input name="table_number" label="Nomor meja" placeholder="Contoh: T03" />
            <x-form.input name="notes" label="Catatan umum" />

            <fieldset class="md:col-span-2">
                <legend class="mb-2 text-sm font-semibold text-stone-800">Pilih menu</legend>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($categories as $category)
                        @foreach($category->menus as $menu)
                            <label class="flex flex-col gap-2 rounded-xl border border-stone-200 p-3 transition duration-150 ease-out has-[:checked]:border-coffee-500 has-[:checked]:bg-coffee-50">
                                <span class="flex items-start justify-between gap-2">
                                    <span>
                                        <span class="block font-semibold text-stone-900">{{ $menu->name }}</span>
                                        <span class="block text-sm text-stone-500">Rp{{ number_format($menu->price, 0, ',', '.') }}</span>
                                    </span>
                                    <input type="checkbox" name="items[{{ $menu->id }}][menu_id]" value="{{ $menu->id }}" class="mt-1 size-5 rounded accent-coffee-600">
                                </span>
                                <span class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-medium text-stone-500">Jumlah</span>
                                    <input type="number" name="items[{{ $menu->id }}][quantity]" value="1" min="1" max="20" aria-label="Jumlah {{ $menu->name }}" class="w-20 rounded-lg border border-stone-300 px-2 py-1.5 text-sm">
                                </span>
                            </label>
                        @endforeach
                    @endforeach
                </div>
            </fieldset>

            <div class="md:col-span-2">
                <x-button type="submit" size="lg" class="w-full">Bayar dan Masukkan Antrean</x-button>
            </div>
        </form>
    </x-card>
</div>

<x-card as="section" padding="none" class="mt-6">
    <div class="border-b border-stone-200 p-5 sm:p-6">
        <h2 class="text-lg font-bold text-stone-900">Pesanan Terbaru</h2>
        <p class="mt-0.5 text-sm text-stone-500">Verifikasi pembayaran dan pantau antrean.</p>
    </div>

    <x-data-table :head="['Order', 'Pelanggan', 'Total', 'Antrean', 'Status', 'Aksi']">
        <x-slot:desktop>
            @forelse($orders as $order)
                <tr class="transition hover:bg-stone-50">
                    <td class="px-5 py-4 font-semibold text-stone-900">{{ $order->order_number }}</td>
                    <td class="px-5 py-4 text-stone-700">{{ $order->customer_name }}</td>
                    <td class="px-5 py-4 tabular-nums text-stone-700">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="px-5 py-4 font-bold tabular-nums text-stone-900">{{ $order->queue?->queue_number ?? '-' }}</td>
                    <td class="px-5 py-4"><x-status-badge :status="$order->status" /></td>
                    <td class="px-5 py-4"><x-cashier.order-actions :order="$order" /></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <x-empty-state icon="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" title="Belum ada pesanan" description="Pesanan baru dari pelanggan akan muncul di sini." />
                    </td>
                </tr>
            @endforelse
        </x-slot:desktop>

        <x-slot:mobile>
            @forelse($orders as $order)
                <li class="space-y-3 p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate font-bold text-stone-900">{{ $order->order_number }}</p>
                            <p class="truncate text-sm text-stone-500">{{ $order->customer_name }}</p>
                        </div>
                        <x-status-badge :status="$order->status" />
                    </div>
                    <dl class="flex items-center justify-between gap-4 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-stone-500">Antrean</dt>
                            <dd class="font-bold tabular-nums text-stone-900">{{ $order->queue?->queue_number ?? '-' }}</dd>
                        </div>
                        <div class="text-right">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-stone-500">Total</dt>
                            <dd class="font-bold tabular-nums text-stone-900">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                    <x-cashier.order-actions :order="$order" />
                </li>
            @empty
                <li>
                    <x-empty-state icon="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" title="Belum ada pesanan" description="Pesanan baru dari pelanggan akan muncul di sini." />
                </li>
            @endforelse
        </x-slot:mobile>
    </x-data-table>

    @if($orders->hasPages())
        <div class="border-t border-stone-200 p-4">{{ $orders->links() }}</div>
    @endif
</x-card>
@endsection
