@extends('layouts.dashboard', ['title' => 'Laporan Pemilik'])

@section('content')
@php
    $metrics = [
        ['key' => 'revenue', 'label' => 'Omzet', 'icon' => 'chart-bar', 'tone' => 'coffee', 'money' => true],
        ['key' => 'orders', 'label' => 'Transaksi', 'icon' => 'document-check', 'tone' => 'info', 'money' => false],
        ['key' => 'average_order', 'label' => 'Rata-rata Transaksi', 'icon' => 'chart-pie', 'tone' => 'coffee', 'money' => true],
        ['key' => 'average_wait', 'label' => 'Rata-rata Tunggu', 'icon' => 'clock', 'tone' => 'warning', 'money' => false, 'suffix' => ' menit'],
        ['key' => 'waiting', 'label' => 'Masih Menunggu', 'icon' => 'queue-list', 'tone' => 'neutral', 'money' => false],
        ['key' => 'completed', 'label' => 'Selesai', 'icon' => 'check-circle', 'tone' => 'success', 'money' => false],
    ];
@endphp

<div>
    <p class="text-sm font-medium text-stone-500">Ringkasan penjualan</p>
    <h1 class="mt-1 text-2xl font-bold tracking-tight text-stone-900">Laporan Pemilik</h1>
    <p class="mt-1 text-sm text-stone-500">Pilih periode untuk melihat performa penjualan dan antrean.</p>
</div>

<x-card as="section" class="mt-6">
    <form method="GET" action="{{ route('owner.index') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_auto_auto]">
        <x-form.input name="from" label="Dari tanggal" type="date" :value="$from" required />
        <x-form.input name="to" label="Sampai tanggal" type="date" :value="$to" required />
        <div class="flex items-end">
            <x-button type="submit" class="w-full">
                <x-icon name="search" class="size-4" /> Tampilkan
            </x-button>
        </div>
        <div class="flex items-end">
            <x-button :href="route('owner.export', ['from' => $from, 'to' => $to])" variant="outline" class="w-full">
                <x-icon name="download" class="size-4" /> Export CSV
            </x-button>
        </div>
    </form>
</x-card>

<p class="mt-4 flex items-center gap-2 text-sm text-stone-500">
    <x-icon name="calendar" class="size-4" />
    <span>Periode {{ $from }} sampai {{ $to }} · zona waktu {{ config('app.timezone') }}</span>
</p>

<div class="mt-3 grid grid-cols-2 gap-4 xl:grid-cols-6">
    @foreach($metrics as $metric)
        @php
            $raw = $stats[$metric['key']];
            $value = $metric['money']
                ? 'Rp'.number_format($raw, 0, ',', '.')
                : number_format($raw, 1, ',', '.').($metric['suffix'] ?? '');
        @endphp
        <x-stat-card :label="$metric['label']" :value="$value" :icon="$metric['icon']" :tone="$metric['tone']" />
    @endforeach
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <x-card as="section" padding="none">
        <div class="border-b border-stone-200 px-5 py-4 sm:px-6">
            <h2 class="font-bold text-stone-900">Menu Terlaris</h2>
            <p class="mt-1 text-sm text-stone-500">Diurutkan dari jumlah item terjual.</p>
        </div>
        <div class="p-5 sm:p-6">
            @forelse($topMenus as $menu)
                <div class="flex items-center justify-between gap-4 py-3 {{ !$loop->first ? 'border-t border-stone-100' : '' }}">
                    <span class="flex min-w-0 items-center gap-3">
                        <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-coffee-50 text-xs font-bold text-coffee-700 tabular-nums" aria-hidden="true">{{ $loop->iteration }}</span>
                        <span class="truncate font-medium text-stone-800">{{ $menu->menu_name_snapshot }}</span>
                    </span>
                    <span class="shrink-0 text-right text-sm">
                        <strong class="block font-semibold text-stone-900">{{ $menu->total_quantity }} item</strong>
                        <span class="text-stone-500">Rp{{ number_format($menu->total_sales, 0, ',', '.') }}</span>
                    </span>
                </div>
            @empty
                <x-empty-state
                    icon="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"
                    title="Belum ada penjualan valid"
                    description="Tidak ada transaksi lunas pada periode ini."
                />
            @endforelse
        </div>
    </x-card>

    <x-card as="section" padding="none">
        <div class="border-b border-stone-200 px-5 py-4 sm:px-6">
            <h2 class="font-bold text-stone-900">Transaksi Valid Terbaru</h2>
            <p class="mt-1 text-sm text-stone-500">Hanya pesanan yang sudah dibayar.</p>
        </div>
        <div class="p-5 sm:p-6">
            @forelse($recent as $order)
                <div class="flex justify-between gap-3 py-3 {{ !$loop->first ? 'border-t border-stone-100' : '' }}">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-stone-800">{{ $order->customer_name }}</p>
                        <p class="text-xs text-stone-500">{{ $order->order_number }} · {{ $order->ordered_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="shrink-0 text-sm font-semibold tabular-nums text-stone-900">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            @empty
                <x-empty-state
                    icon="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z"
                    title="Belum ada transaksi"
                    description="Belum ada pesanan lunas pada periode ini."
                />
            @endforelse
        </div>
    </x-card>
</div>
@endsection
