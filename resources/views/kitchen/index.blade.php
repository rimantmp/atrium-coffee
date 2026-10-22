@extends('layouts.dashboard', ['title' => 'Dashboard Dapur'])

@section('content')
<h1 class="sr-only">Dashboard Dapur</h1>

@php
    $waitingMinutes = fn ($queue) => max(0, (int) $queue->queued_at->diffInMinutes(now()));
    $isOverdue = fn ($queue) => $waitingMinutes($queue) > (int) $queue->order->estimated_processing_minutes;
@endphp

<div class="grid gap-6 xl:grid-cols-[2fr_1fr]">
    <x-card as="section" padding="none" class="p-5 sm:p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-coffee-600">Sedang Diproses</p>
                <h2 class="mt-1 text-xl font-bold text-stone-900">{{ $current ? 'Order Aktif' : 'Belum Ada Order Aktif' }}</h2>
            </div>
            @if($current)
                <x-priority-chip :priority="$current->base_priority" />
            @endif
        </div>

        @if($current)
            <div class="mt-6">
                <p class="text-6xl font-bold tabular-nums text-coffee-900">{{ $current->queue_number }}</p>
                <p class="mt-2 text-sm text-stone-500">
                    Order <span class="font-semibold text-stone-700">{{ $current->order->order_number }}</span>
                    · mulai {{ $current->processing_started_at?->format('H:i') }}
                    · estimasi {{ $current->order->estimated_processing_minutes }} menit
                </p>
                @php($runningMinutes = max(0, (int) $current->processing_started_at?->diffInMinutes(now())))
                @if($runningMinutes > (int) $current->order->estimated_processing_minutes)
                    <p class="mt-3">
                        <x-badge variant="warning">Lewat estimasi {{ $runningMinutes - (int) $current->order->estimated_processing_minutes }} menit</x-badge>
                    </p>
                @endif
            </div>

            <ul class="mt-6 divide-y divide-stone-100 rounded-xl bg-stone-50 px-4">
                @foreach($current->order->items as $item)
                    <li class="py-4">
                        <p class="font-semibold text-stone-900"><span class="text-coffee-700">{{ $item->quantity }}×</span> {{ $item->menu_name_snapshot }}</p>
                        @if($item->notes)
                            <p class="mt-1 text-sm text-amber-800">Catatan: {{ $item->notes }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('kitchen.ready', $current) }}" class="mt-6">
                @csrf
                <x-button type="submit" size="lg" class="w-full min-h-14 text-lg">Siap Diambil</x-button>
            </form>
        @else
            <div class="mt-8 rounded-2xl border border-dashed border-stone-300 p-8 text-center">
                <p class="text-stone-600">Antrean berikutnya dipilih otomatis dengan urutan Prioritas, lalu FCFS, lalu Aging.</p>
                <form method="POST" action="{{ route('kitchen.claim') }}" class="mt-5">
                    @csrf
                    <x-button type="submit" size="lg" class="min-h-14">Mulai Order Berikutnya</x-button>
                </form>
            </div>
        @endif
    </x-card>

    <x-card as="section">
        <p class="text-xs font-semibold uppercase tracking-wider text-coffee-600">Antrean Berikutnya</p>
        @if($waiting->first())
            @php($next = $waiting->first())
            <p class="mt-5 text-5xl font-bold tabular-nums text-stone-900">{{ $next->queue_number }}</p>
            <div class="mt-3 flex flex-wrap gap-2">
                <x-priority-chip :priority="$scheduler->effectivePriority($next)" suffix="Efektif" />
            </div>
            @if($scheduler->effectivePriority($next) < $next->base_priority)
                <p class="mt-2 text-xs font-medium text-stone-500">Naik otomatis karena waktu tunggu.</p>
            @endif
            <p class="mt-4 text-sm text-stone-600">
                Menunggu {{ $waitingMinutes($next) }} menit · estimasi {{ $next->order->estimated_processing_minutes }} menit
            </p>
        @else
            <p class="mt-6 rounded-xl bg-stone-50 p-6 text-center text-sm text-stone-500">Antrean kosong.</p>
        @endif
    </x-card>
</div>

<x-card as="section" class="mt-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-stone-900">Antrean Menunggu</h2>
        <x-badge variant="neutral">{{ $waiting->count() }}</x-badge>
    </div>

    @if($waiting->isEmpty())
        <x-empty-state icon="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" title="Tidak ada order menunggu" description="Semua antrean sudah ditangani." class="mt-4" />
    @else
        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($waiting as $queue)
                <article class="rounded-xl border border-stone-200 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-2xl font-bold tabular-nums text-stone-900">{{ $queue->queue_number }}</p>
                        <x-priority-chip :priority="$scheduler->effectivePriority($queue)" />
                    </div>
                    <p class="mt-3 text-sm font-medium text-stone-700">{{ $queue->order->items->map(fn ($item) => $item->quantity.'× '.$item->menu_name_snapshot)->join(', ') }}</p>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <p class="text-xs text-stone-500">Menunggu {{ $waitingMinutes($queue) }} menit</p>
                        @if($isOverdue($queue))
                            <x-badge variant="warning">Lewat estimasi</x-badge>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</x-card>

@if($ready->isNotEmpty())
    <section class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 sm:p-6">
        <h2 class="font-bold text-emerald-900">Siap Diambil</h2>
        <div class="mt-3 flex flex-wrap gap-2">
            @foreach($ready as $queue)
                <span class="rounded-xl bg-white px-4 py-2 font-bold tabular-nums text-emerald-800 shadow-sm">{{ $queue->queue_number }}</span>
            @endforeach
        </div>
    </section>
@endif
@endsection
