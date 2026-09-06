<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\QueueStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\QueueEntry;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QueueService
{
    public function __construct(private readonly AuditService $audit, private readonly NotificationOutboxService $notifications) {}

    public function requestPayment(Order $order, string $method): Payment
    {
        return DB::transaction(function () use ($order, $method) {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            if ($order->queue) {
                return $order->payments()->latest()->firstOrFail();
            }
            $existing = $order->payments()->where('status', PaymentStatus::Pending->value)->latest()->first();
            if ($existing) {
                return $existing;
            }
            abort_unless($order->status === OrderStatus::PendingPayment, 409, 'Pembayaran tidak dapat diajukan pada status ini.');
            $payment = Payment::create(['order_id' => $order->id, 'payment_number' => 'PAY-'.now()->format('YmdHis').'-'.$order->id, 'method' => $method, 'amount' => $order->total_amount, 'status' => PaymentStatus::Pending->value]);
            $order->update(['payment_status' => PaymentStatus::Pending, 'status' => OrderStatus::WaitingVerification]);
            $this->history($order->id, OrderStatus::PendingPayment->value, OrderStatus::WaitingVerification->value, null);
            $this->audit->record('PAYMENT_SUBMITTED', $payment, [], ['method' => $method, 'status' => PaymentStatus::Pending->value]);

            return $payment;
        });
    }

    public function verifyPayment(Payment $payment, int $userId): QueueEntry
    {
        return $this->payAndEnqueue($payment->order, $payment->method, $userId, $payment);
    }

    public function payAndEnqueue(Order $order, string $method, ?int $userId = null, ?Payment $payment = null): QueueEntry
    {
        return DB::transaction(function () use ($order, $method, $userId, $payment) {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            if ($order->queue) {
                return $order->queue;
            }
            if ($order->status === OrderStatus::Cancelled) {
                throw ValidationException::withMessages(['order' => 'Pesanan yang dibatalkan tidak dapat dibayar.']);
            }

            $payment = $payment ? Payment::query()->lockForUpdate()->findOrFail($payment->id) : new Payment;
            if ($payment->exists && $payment->status === PaymentStatus::Paid->value) {
                return $order->queue;
            }
            $payment->fill([
                'order_id' => $order->id,
                'payment_number' => $payment->payment_number ?: 'PAY-'.now()->format('YmdHis').'-'.$order->id,
                'method' => $method,
                'amount' => $order->total_amount,
                'status' => PaymentStatus::Paid->value,
                'paid_at' => now(),
                'verified_at' => now(),
                'verified_by_user_id' => $userId,
            ])->save();

            $date = now(config('app.timezone'))->toDateString();
            DB::table('daily_counters')->insertOrIgnore([
                'counter_date' => $date, 'counter_type' => 'QUEUE', 'last_value' => 0, 'updated_at' => now(),
            ]);
            $counter = DB::table('daily_counters')->where(['counter_date' => $date, 'counter_type' => 'QUEUE'])->lockForUpdate()->first();
            $sequence = $counter->last_value + 1;
            DB::table('daily_counters')->where(['counter_date' => $date, 'counter_type' => 'QUEUE'])->update(['last_value' => $sequence, 'updated_at' => now()]);

            $queue = QueueEntry::create([
                'order_id' => $order->id,
                'queue_date' => $date,
                'sequence_number' => $sequence,
                'queue_number' => 'A'.str_pad((string) $sequence, 3, '0', STR_PAD_LEFT),
                'base_priority' => $order->base_priority,
                'status' => QueueStatus::Waiting,
                'queued_at' => now(),
            ]);

            $fromStatus = $order->status->value;
            $order->update(['payment_status' => PaymentStatus::Paid, 'status' => OrderStatus::Queued, 'paid_at' => now()]);
            $this->history($order->id, $fromStatus, OrderStatus::Queued->value, $userId);
            DB::table('queue_histories')->insert(['queue_id' => $queue->id, 'from_status' => null, 'to_status' => QueueStatus::Waiting->value, 'changed_by_user_id' => $userId, 'created_at' => now()]);
            $this->audit->record('ORDER_PAID_AND_QUEUED', $order, ['status' => 'PENDING_PAYMENT'], ['status' => 'QUEUED', 'queue_number' => $queue->queue_number], null, $userId);

            return $queue;
        });
    }

    public function markReady(QueueEntry $queue, int $userId): QueueEntry
    {
        return DB::transaction(function () use ($queue, $userId) {
            $queue = QueueEntry::query()->lockForUpdate()->findOrFail($queue->id);
            abort_unless($queue->status === QueueStatus::Processing, 409, 'Hanya pesanan yang sedang diproses dapat ditandai siap.');
            $queue->update(['status' => QueueStatus::Ready, 'ready_at' => now()]);
            $queue->order()->update(['status' => OrderStatus::Ready]);
            DB::table('queue_histories')->insert(['queue_id' => $queue->id, 'from_status' => 'PROCESSING', 'to_status' => 'READY', 'changed_by_user_id' => $userId, 'created_at' => now()]);
            $this->history($queue->order_id, 'PROCESSING', 'READY', $userId);
            $this->audit->record('ORDER_READY', $queue, ['status' => 'PROCESSING'], ['status' => 'READY'], null, $userId);
            $this->notifications->enqueue($queue->order, 'ORDER_READY', ['status' => 'READY', 'queue_number' => $queue->queue_number]);

            return $queue;
        });
    }

    public function complete(QueueEntry $queue, int $userId): QueueEntry
    {
        return DB::transaction(function () use ($queue, $userId) {
            $queue = QueueEntry::query()->lockForUpdate()->findOrFail($queue->id);
            abort_unless($queue->status === QueueStatus::Ready, 409, 'Hanya pesanan siap yang dapat diselesaikan.');
            $queue->update(['status' => QueueStatus::Completed, 'completed_at' => now()]);
            $queue->order()->update(['status' => OrderStatus::Completed]);
            DB::table('queue_histories')->insert(['queue_id' => $queue->id, 'from_status' => 'READY', 'to_status' => 'COMPLETED', 'changed_by_user_id' => $userId, 'created_at' => now()]);
            $this->history($queue->order_id, 'READY', 'COMPLETED', $userId);
            $this->audit->record('ORDER_COMPLETED', $queue, ['status' => 'READY'], ['status' => 'COMPLETED'], null, $userId);
            $this->notifications->enqueue($queue->order, 'ORDER_COMPLETED', ['status' => 'COMPLETED', 'queue_number' => $queue->queue_number]);

            return $queue;
        });
    }

    public function cancel(Order $order, int $userId, string $reason): Order
    {
        return DB::transaction(function () use ($order, $userId, $reason) {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            abort_unless(in_array($order->status, [OrderStatus::PendingPayment, OrderStatus::WaitingVerification, OrderStatus::Queued], true), 409, 'Pesanan pada status ini tidak dapat dibatalkan.');
            $from = $order->status->value;

            if ($order->queue) {
                $queue = QueueEntry::query()->lockForUpdate()->findOrFail($order->queue->id);
                abort_unless($queue->status === QueueStatus::Waiting, 409, 'Antrean yang sudah diproses tidak dapat dibatalkan otomatis.');
                $queue->update(['status' => QueueStatus::Cancelled, 'cancelled_at' => now()]);
                DB::table('queue_histories')->insert(['queue_id' => $queue->id, 'from_status' => 'WAITING', 'to_status' => 'CANCELLED', 'changed_by_user_id' => $userId, 'reason' => $reason, 'created_at' => now()]);
            }

            $order->update(['status' => OrderStatus::Cancelled, 'cancelled_at' => now(), 'cancel_reason' => $reason]);
            $this->history($order->id, $from, 'CANCELLED', $userId);
            $this->audit->record('ORDER_CANCELLED', $order, ['status' => $from], ['status' => 'CANCELLED'], $reason, $userId);

            return $order;
        });
    }

    private function history(int $orderId, ?string $from, string $to, ?int $userId): void
    {
        DB::table('order_status_histories')->insert(['order_id' => $orderId, 'from_status' => $from, 'to_status' => $to, 'changed_by_user_id' => $userId, 'created_at' => now()]);
    }
}
