@extends('layouts.dashboard', ['title' => 'Dashboard Admin'])

@section('content')
@php
    $metricCards = [
        ['key' => 'menus', 'label' => 'Total Menu', 'tone' => 'coffee', 'icon' => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25'],
        ['key' => 'available', 'label' => 'Menu Tersedia', 'tone' => 'success', 'icon' => 'm4.5 12.75 6 6 9-13.5'],
        ['key' => 'orders', 'label' => 'Order Hari Ini', 'tone' => 'info', 'icon' => 'M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z'],
        ['key' => 'revenue', 'label' => 'Omzet Hari Ini', 'tone' => 'warning', 'icon' => 'M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
    ];
    $chartPoints = $weeklyTrend->values()->map(fn ($day, $index) => (30 + ($index * 105)).','.round(200 - (($day['orders'] / $maxWeeklyOrders) * 150)))->join(' ');
    $chartAreaPoints = '30,200 '.$chartPoints.' 660,200';
    $todayOrders = max(1, $stats['orders']);
    $statusStops = [];
    $statusOffset = 0;
    foreach ($statusSummary as $status) {
        $nextOffset = $statusOffset + (($status['count'] / $todayOrders) * 100);
        $statusStops[] = $status['color'].' '.$statusOffset.'% '.$nextOffset.'%';
        $statusOffset = $nextOffset;
    }
    if ($stats['orders'] === 0) {
        $statusStops = ['#e7d3bd 0% 100%'];
    }
@endphp

<div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
    <div>
        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-stone-500">Ringkasan Operasional</p>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-stone-900 sm:text-3xl">Selamat datang, {{ auth()->user()->name }}</h1>
        <p class="mt-1 text-sm text-stone-500">Berikut ringkasan operasional kedai hari ini.</p>
    </div>
    <blockquote class="hidden max-w-xs items-start gap-3 text-right font-serif italic text-stone-500 xl:flex">
        <span class="mt-2 h-px w-9 shrink-0 bg-stone-400" aria-hidden="true"></span>
        &ldquo;Kopi yang baik,<br>membuat hari lebih baik.&rdquo;
    </blockquote>
</div>

<div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach($metricCards as $metric)
        <x-stat-card
            :label="$metric['label']"
            :value="$metric['key'] === 'revenue' ? 'Rp'.number_format($stats[$metric['key']], 0, ',', '.') : $stats[$metric['key']]"
            :tone="$metric['tone']"
            :icon="$metric['icon']"
            hint="Diperbarui hari ini"
        />
    @endforeach
</div>

<div class="mt-4 grid gap-4 xl:grid-cols-[minmax(0,1.35fr)_minmax(390px,0.85fr)]">
    <x-card as="section">
        <div class="flex items-start justify-between gap-4">
            <div class="flex gap-3">
                <span class="mt-0.5 text-coffee-600" aria-hidden="true"><x-icon name="chart-bar" class="size-6" /></span>
                <div>
                    <h2 class="text-lg font-extrabold text-stone-900">Tren Order Mingguan</h2>
                    <p class="text-sm text-stone-500">Jumlah order selama tujuh hari terakhir.</p>
                </div>
            </div>
            <x-badge variant="coffee">7 Hari</x-badge>
        </div>
        <div class="mt-5 overflow-x-auto">
            <svg viewBox="0 0 690 250" class="w-full min-w-[520px]" role="img" aria-label="Grafik tren order mingguan">
                <defs>
                    <linearGradient id="chart-fill" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#8b563e" stop-opacity=".22"/>
                        <stop offset="1" stop-color="#8b563e" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                @foreach([50, 100, 150, 200] as $line)
                    <line x1="30" y1="{{ $line }}" x2="660" y2="{{ $line }}" stroke="#e7e3dc" stroke-width="1"/>
                @endforeach
                <polygon points="{{ $chartAreaPoints }}" fill="url(#chart-fill)"/>
                <polyline points="{{ $chartPoints }}" fill="none" stroke="#8b563e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                @foreach($weeklyTrend->values() as $day)
                    <circle cx="{{ 30 + ($loop->index * 105) }}" cy="{{ round(200 - (($day['orders'] / $maxWeeklyOrders) * 150)) }}" r="5" fill="#8b563e" stroke="white" stroke-width="2">
                        <title>{{ $day['orders'] }} order</title>
                    </circle>
                    <text x="{{ 30 + ($loop->index * 105) }}" y="228" text-anchor="middle" fill="#78716c" font-size="11">{{ $day['label'] }}</text>
                @endforeach
            </svg>
        </div>
        <div class="flex justify-center text-xs text-stone-500">
            <span class="flex items-center gap-2"><i class="size-2.5 rounded-full bg-coffee-600" aria-hidden="true"></i>Order minggu ini</span>
        </div>
    </x-card>

    <x-card as="section" padding="none" class="overflow-hidden">
        <div class="flex items-start justify-between border-b border-stone-200 p-5">
            <div class="flex gap-3">
                <span class="grid size-10 place-items-center rounded-xl bg-coffee-50 text-coffee-700" aria-hidden="true"><x-icon name="document-check" /></span>
                <div>
                    <h2 class="text-lg font-extrabold text-stone-900">Pesanan Terbaru</h2>
                    <p class="text-sm text-stone-500">Aktivitas pesanan terkini.</p>
                </div>
            </div>
            <a href="{{ route('cashier.index') }}" class="mt-2 shrink-0 text-xs font-bold text-coffee-700 hover:text-coffee-800">Lihat semua</a>
        </div>
        <div class="divide-y divide-stone-200">
            @forelse($recentOrders as $order)
                <div class="flex items-center gap-3 px-5 py-3">
                    <span class="grid size-10 shrink-0 place-items-center rounded-full bg-coffee-50 text-coffee-700" aria-hidden="true"><x-icon name="coffee" /></span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-extrabold text-stone-900">{{ $order->order_number }}</p>
                        <p class="truncate text-xs text-stone-500">{{ $order->customer_name }} · {{ $order->items->pluck('menu_name_snapshot')->join(', ') }}</p>
                    </div>
                    <x-status-badge :status="$order->status" />
                    <div class="min-w-20 text-right">
                        <p class="text-xs font-extrabold tabular-nums text-stone-900">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <p class="text-[11px] text-stone-500">{{ $order->ordered_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="px-6 py-12 text-center text-sm text-stone-500">Belum ada pesanan terbaru.</p>
            @endforelse
        </div>
    </x-card>
</div>

<div class="mt-4 grid gap-4 xl:grid-cols-2">
    <x-card as="section">
        <div class="flex items-start justify-between gap-4">
            <div class="flex gap-3">
                <span class="text-coffee-600" aria-hidden="true"><x-icon name="trophy" class="size-6" /></span>
                <div>
                    <h2 class="text-lg font-extrabold text-stone-900">Menu Terlaris</h2>
                    <p class="text-sm text-stone-500">Berdasarkan jumlah order hari ini.</p>
                </div>
            </div>
            <a href="{{ route('admin.menus.index') }}" class="shrink-0 text-xs font-bold text-coffee-700 hover:text-coffee-800">Lihat semua</a>
        </div>
        <div class="mt-5 space-y-4">
            @forelse($bestSellers as $item)
                <div class="grid grid-cols-[28px_minmax(0,1fr)_auto] items-center gap-3">
                    <span class="grid size-7 place-items-center rounded-full bg-coffee-100 text-xs font-bold text-coffee-800">{{ $loop->iteration }}</span>
                    <span class="min-w-0">
                        <span class="flex items-center justify-between gap-3">
                            <span class="truncate text-sm font-semibold text-stone-900">{{ $item->name }}</span>
                            <span class="shrink-0 text-xs text-stone-500">{{ $item->quantity }} order</span>
                        </span>
                        <span class="mt-1.5 block h-2 overflow-hidden rounded-full bg-stone-100">
                            <span class="block h-full rounded-full bg-coffee-600" style="width: {{ ($item->quantity / $maxBestSellerQuantity) * 100 }}%"></span>
                        </span>
                    </span>
                </div>
            @empty
                <p class="py-8 text-center text-sm text-stone-500">Belum ada data penjualan hari ini.</p>
            @endforelse
        </div>
    </x-card>

    <x-card as="section">
        <div class="flex gap-3">
            <span class="text-coffee-600" aria-hidden="true"><x-icon name="chart-pie" class="size-6" /></span>
            <div>
                <h2 class="text-lg font-extrabold text-stone-900">Status Pesanan</h2>
                <p class="text-sm text-stone-500">Distribusi status pesanan hari ini.</p>
            </div>
        </div>
        <div class="mt-5 flex flex-col items-center justify-center gap-7 sm:flex-row">
            <div class="relative grid size-36 shrink-0 place-items-center rounded-full" style="background: conic-gradient({{ implode(', ', $statusStops) }})">
                <span class="grid size-24 place-items-center rounded-full bg-white text-center">
                    <span>
                        <strong class="block text-2xl tabular-nums text-stone-900">{{ $stats['orders'] }}</strong>
                        <small class="text-xs text-stone-500">Total Order</small>
                    </span>
                </span>
            </div>
            <ul class="space-y-3">
                @foreach($statusSummary as $status)
                    <li class="grid grid-cols-[12px_90px_auto_auto] items-center gap-2 text-sm">
                        <i class="size-3 rounded-full" style="background: {{ $status['color'] }}" aria-hidden="true"></i>
                        <span class="font-semibold text-stone-800">{{ $status['label'] }}</span>
                        <strong class="tabular-nums text-stone-900">{{ $status['count'] }}</strong>
                        <span class="text-xs text-stone-500">{{ $stats['orders'] ? round(($status['count'] / $stats['orders']) * 100) : 0 }}%</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </x-card>
</div>
@endsection
