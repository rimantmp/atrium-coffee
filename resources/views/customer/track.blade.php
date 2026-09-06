@extends('layouts.customer', ['title' => 'Status Pesanan'])
@section('content')
<div data-tracking-url="{{ route('orders.status', $order->tracking_token) }}" class="mx-auto max-w-3xl">
    <section class="rounded-3xl border border-stone-200 bg-white p-6 text-center shadow-sm sm:p-10">
        <p class="text-sm font-semibold uppercase tracking-wider text-coffee-600">Antrean Anda</p>
        <p data-queue-number class="mt-4 text-6xl font-bold tabular-nums text-coffee-900">{{ $order->queue?->queue_number ?? '—' }}</p>
        <span data-status-label class="mt-4 inline-flex rounded-full bg-coffee-100 px-3 py-1.5 text-sm font-semibold text-coffee-800">{{ $order->status->label() }}</span>
        <p class="mt-4 text-sm text-stone-600">Order <span class="font-semibold">{{ $order->order_number }}</span></p>
    </section>

    @if($order->status->value === 'PENDING_PAYMENT')
    <section class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5"><h2 class="font-semibold text-amber-900">Selesaikan pembayaran</h2><p class="mt-1 text-sm text-amber-800">Pilih metode pembayaran simulasi MVP. Setelah berhasil, nomor antrean dibuat satu kali.</p>
        <form method="POST" action="{{ route('orders.pay', $order->tracking_token) }}" class="mt-4 flex flex-col gap-3 sm:flex-row">@csrf<select name="method" class="min-h-11 flex-1 rounded-xl border border-amber-300 bg-white px-3"><option value="CASH">Tunai / Bayar di Kasir</option><option value="QRIS">QRIS (simulasi)</option></select><button class="min-h-11 rounded-xl bg-coffee-600 px-5 font-semibold text-white">Konfirmasi Pembayaran</button></form>
    </section>
    @elseif($order->status->value === 'WAITING_VERIFICATION')
    <section class="mt-6 rounded-2xl border border-sky-200 bg-sky-50 p-5"><h2 class="font-semibold text-sky-900">Menunggu verifikasi kasir</h2><p class="mt-1 text-sm text-sky-800">Pembayaran sudah diajukan. Nomor antrean akan muncul setelah kasir melakukan verifikasi.</p></section>
    @endif

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm"><h2 class="font-semibold">Progres Pesanan</h2><ol class="mt-4 space-y-4 text-sm">@foreach(['Pesanan Diterima','Dalam Antrean','Diproses','Siap Diambil','Selesai'] as $step)<li class="flex items-center gap-3"><span class="grid size-7 place-items-center rounded-full bg-coffee-100 font-semibold text-coffee-700">{{ $loop->iteration }}</span>{{ $step }}</li>@endforeach</ol></section>
        <section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm"><h2 class="font-semibold">Ringkasan</h2><ul class="mt-4 divide-y divide-stone-100">@foreach($order->items as $item)<li class="flex justify-between gap-4 py-3 text-sm"><span>{{ $item->quantity }}× {{ $item->menu_name_snapshot }}</span><span class="tabular-nums">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span></li>@endforeach</ul><div class="mt-4 flex justify-between border-t border-stone-200 pt-4 font-semibold"><span>Total</span><span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span></div></section>
    </div>
</div>
@endsection
