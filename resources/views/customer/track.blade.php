@extends('layouts.customer', ['title' => 'Status Pesanan'])

@section('content')
@php
    /** Urutan tahap produksi (DESIGN.md 17.3); status sebelum ini ditampilkan sebagai status pra-produksi. */
    $steps = ['Pesanan Diterima', 'Dalam Antrean', 'Diproses', 'Siap Diambil', 'Selesai'];
    $statusIndex = match ($order->status->value) {
        'PENDING_PAYMENT', 'WAITING_VERIFICATION' => -1,
        'QUEUED' => 1,
        'PROCESSING' => 2,
        'READY' => 3,
        'COMPLETED' => 4,
        default => -1,
    };
    $isCancelled = $order->status->value === 'CANCELLED';
@endphp

<div data-tracking-url="{{ route('orders.status', $order->tracking_token) }}" class="mx-auto max-w-3xl">
    <section class="rounded-2xl border border-stone-200 bg-white p-6 text-center shadow-sm sm:p-10">
        <p class="text-sm font-semibold uppercase tracking-wider text-coffee-600">Antrean Anda</p>
        <p data-queue-number class="mt-4 text-6xl font-bold tabular-nums text-coffee-900">{{ $order->queue?->queue_number ?? '-' }}</p>
        <div class="mt-4">
            <x-status-badge :status="$order->status" data-status-label aria-live="polite" />
        </div>
        <p class="mt-4 text-sm text-stone-600">Order <span class="font-semibold text-stone-900">{{ $order->order_number }}</span></p>
    </section>

    <x-alert variant="warning" title="Koneksi terputus" data-tracking-error hidden class="mt-6">
        Status tidak bisa diperbarui otomatis. Tampilan terakhir tetap ditampilkan; halaman akan mencoba lagi.
    </x-alert>

    @if($order->status->value === 'PENDING_PAYMENT')
        <section class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <h2 class="font-bold text-amber-900">Selesaikan pembayaran</h2>
            <p class="mt-1 text-sm text-amber-800">Pilih metode pembayaran simulasi MVP. Setelah berhasil, nomor antrean dibuat satu kali.</p>
            <form method="POST" action="{{ route('orders.pay', $order->tracking_token) }}" class="mt-4 flex flex-col gap-3 sm:flex-row">
                @csrf
                <x-form.select name="method" label="Metode pembayaran" class="flex-1">
                    <option value="CASH">Tunai / Bayar di Kasir</option>
                    <option value="QRIS">QRIS (simulasi)</option>
                </x-form.select>
                <x-button type="submit" size="lg" class="sm:self-end">Konfirmasi Pembayaran</x-button>
            </form>
        </section>
    @elseif($order->status->value === 'WAITING_VERIFICATION')
        <section class="mt-6 rounded-2xl border border-sky-200 bg-sky-50 p-5">
            <h2 class="font-bold text-sky-900">Menunggu verifikasi kasir</h2>
            <p class="mt-1 text-sm text-sky-800">Pembayaran sudah diajukan. Nomor antrean akan muncul setelah kasir melakukan verifikasi.</p>
        </section>
    @endif

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <x-card as="section">
            <h2 class="font-bold text-stone-900">Progres Pesanan</h2>

            @if($isCancelled)
                <div class="mt-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4">
                    <x-icon name="x-circle" class="size-5 shrink-0 text-red-700" />
                    <div>
                        <p class="font-semibold text-red-900">Pesanan dibatalkan</p>
                        <p class="mt-0.5 text-sm text-red-800">Pesanan ini tidak lagi diproses. Hubungi kasir bila perlu membuat pesanan baru.</p>
                    </div>
                </div>
            @else
                <ol class="mt-4 space-y-4 text-sm">
                    @foreach($steps as $step)
                        @php($stepIndex = $loop->iteration - 1)
                        @php($isDone = $statusIndex >= $stepIndex)
                        @php($isCurrent = $statusIndex === $stepIndex)
                        <li class="flex items-center gap-3">
                            <span @class([
                                'grid size-7 shrink-0 place-items-center rounded-full font-semibold',
                                'bg-coffee-600 text-white' => $isDone,
                                'bg-stone-100 text-stone-600' => ! $isDone,
                            ])>
                                @if($isDone && ! $isCurrent)
                                    <x-icon name="check" class="size-4" />
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </span>
                            <span @class([
                                'font-semibold text-stone-900' => $isCurrent,
                                'text-stone-700' => $isDone && ! $isCurrent,
                                'text-stone-500' => ! $isDone,
                            ])>{{ $step }}</span>
                            @if($isCurrent)
                                <span class="ml-auto text-xs font-semibold uppercase tracking-wide text-coffee-700">Sekarang</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            @endif
        </x-card>

        <x-card as="section">
            <h2 class="font-bold text-stone-900">Ringkasan</h2>
            <ul class="mt-4 divide-y divide-stone-100">
                @foreach($order->items as $item)
                    <li class="flex justify-between gap-4 py-3 text-sm">
                        <span class="text-stone-700">{{ $item->quantity }}× {{ $item->menu_name_snapshot }}</span>
                        <span class="tabular-nums text-stone-900">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4 flex justify-between border-t border-stone-200 pt-4 font-bold text-stone-900">
                <span>Total</span>
                <span class="tabular-nums">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </x-card>
    </div>
</div>
@endsection
