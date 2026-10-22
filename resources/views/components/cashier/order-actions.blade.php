{{--
    Aksi per order. Dipakai dua kali (baris tabel desktop dan kartu mobile),
    jadi hidup di satu partial supaya kedua tata letak tidak bisa menyimpang.
--}}
<div class="flex flex-wrap items-center gap-2" x-data="{ panel: null }">
    @if($order->status->value === 'WAITING_VERIFICATION' && ($payment = $order->payments->firstWhere('status', 'PENDING')))
        <form method="POST" action="{{ route('cashier.payments.verify', $payment) }}">
            @csrf
            <x-button type="submit" size="sm" class="bg-amber-500 hover:bg-amber-600 active:bg-amber-700">Verifikasi Bayar</x-button>
        </form>
    @endif

    @if($order->queue?->status?->value === 'READY')
        <form method="POST" action="{{ route('cashier.complete', $order->queue) }}">
            @csrf
            <x-button type="submit" size="sm" class="bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800">Selesaikan</x-button>
        </form>
    @endif

    <x-button :href="route('orders.track', $order->tracking_token)" variant="text" size="sm">Lihat</x-button>

    @if($order->status->value === 'QUEUED' && ($paidPayment = $order->payments->firstWhere('status', 'PAID')))
        <x-button type="button" variant="text" size="sm" class="text-red-700 hover:bg-red-50" @click="panel = panel === 'refund' ? null : 'refund'" aria-label="Refund pesanan {{ $order->order_number }}">Refund</x-button>
        <form x-cloak x-show="panel === 'refund'" method="POST" action="{{ route('cashier.payments.refund', $paidPayment) }}" class="flex w-full flex-wrap items-end gap-2">
            @csrf
            <div class="min-w-56 flex-1">
                <label for="refund-reason-{{ $order->id }}" class="mb-1 block text-xs font-semibold text-stone-700">Alasan refund</label>
                <input id="refund-reason-{{ $order->id }}" name="reason" required minlength="5" class="ui-input min-h-11">
            </div>
            <x-button type="submit" size="sm" variant="danger" class="min-h-11">Proses Refund</x-button>
        </form>
    @endif

    @if(in_array($order->status->value, ['PENDING_PAYMENT', 'WAITING_VERIFICATION', 'QUEUED'], true))
        <x-button type="button" variant="text" size="sm" class="text-red-700 hover:bg-red-50" @click="panel = panel === 'cancel' ? null : 'cancel'" aria-label="Batalkan pesanan {{ $order->order_number }}">Batal</x-button>
        <form x-cloak x-show="panel === 'cancel'" method="POST" action="{{ route('cashier.cancel', $order) }}" class="flex w-full flex-wrap items-end gap-2">
            @csrf
            <div class="min-w-56 flex-1">
                <label for="cancel-reason-{{ $order->id }}" class="mb-1 block text-xs font-semibold text-stone-700">Alasan pembatalan</label>
                <input id="cancel-reason-{{ $order->id }}" name="reason" required class="ui-input min-h-11">
            </div>
            <x-button type="submit" size="sm" variant="danger" class="min-h-11">Konfirmasi Batal</x-button>
        </form>
    @endif
</div>
