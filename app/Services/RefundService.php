<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\QueueStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\QueueEntry;
use App\Models\Refund;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RefundService
{
    public function __construct(private readonly AuditService $audit) {}

    public function refund(Payment $payment, int $userId, string $reason): Refund
    {
        return DB::transaction(function () use ($payment, $userId, $reason) {
            $payment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
            $existing = Refund::query()->where('payment_id', $payment->id)->first();
            if ($existing) {
                return $existing;
            }
            $order = Order::query()->lockForUpdate()->findOrFail($payment->order_id);
            if ($payment->status !== PaymentStatus::Paid->value || $order->status !== OrderStatus::Queued) {
                throw ValidationException::withMessages(['refund' => 'Refund hanya dapat dilakukan untuk order lunas yang masih menunggu dalam antrean.']);
            }
            $queue = QueueEntry::query()->where('order_id', $order->id)->lockForUpdate()->firstOrFail();
            if ($queue->status !== QueueStatus::Waiting) {
                throw ValidationException::withMessages(['refund' => 'Antrean yang sudah diproses tidak dapat direfund otomatis.']);
            }
            $refund = Refund::create(['order_id' => $order->id, 'payment_id' => $payment->id, 'amount' => $payment->amount, 'status' => 'SUCCESS', 'reason' => $reason, 'processed_by_user_id' => $userId, 'processed_at' => now()]);
            $payment->update(['status' => PaymentStatus::Refunded->value]);
            $queue->update(['status' => QueueStatus::Cancelled, 'cancelled_at' => now()]);
            $order->update(['payment_status' => PaymentStatus::Refunded, 'status' => OrderStatus::Cancelled, 'cancelled_at' => now(), 'cancel_reason' => $reason]);
            DB::table('queue_histories')->insert(['queue_id' => $queue->id, 'from_status' => QueueStatus::Waiting->value, 'to_status' => QueueStatus::Cancelled->value, 'changed_by_user_id' => $userId, 'reason' => $reason, 'created_at' => now()]);
            DB::table('order_status_histories')->insert(['order_id' => $order->id, 'from_status' => OrderStatus::Queued->value, 'to_status' => OrderStatus::Cancelled->value, 'changed_by_user_id' => $userId, 'created_at' => now()]);
            $this->audit->record('PAYMENT_REFUNDED', $refund, ['payment_status' => PaymentStatus::Paid->value], ['payment_status' => PaymentStatus::Refunded->value, 'amount' => $payment->amount], $reason, $userId);

            return $refund;
        });
    }
}
