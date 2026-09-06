<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Payment;
use App\Models\QueueEntry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyOrderSeeder extends Seeder
{
    public function run(): void
    {
        if (Order::query()->where('order_number', 'like', '%-D___')->exists()) {
            $this->command?->warn('Data dummy order sudah tersedia; tidak dibuat ulang.');

            return;
        }

        $menus = Menu::query()->orderBy('id')->get();
        $cashier = User::query()->where('role', 'CASHIER')->first();
        $kitchen = User::query()->where('role', 'KITCHEN')->first();

        if ($menus->isEmpty() || ! $cashier || ! $kitchen) {
            $this->command?->warn('Data dummy order dilewati karena menu atau akun staf belum tersedia.');

            return;
        }

        $names = [
            'Andi', 'Bunga', 'Citra', 'Dedi', 'Eka', 'Fajar', 'Gita', 'Hendra', 'Intan', 'Joko',
            'Karin', 'Lukman', 'Maya', 'Nanda', 'Olivia', 'Putra', 'Rani', 'Surya', 'Tania', 'Wahyu',
        ];
        $statuses = [
            'PENDING_PAYMENT', 'PENDING_PAYMENT',
            'CANCELLED', 'CANCELLED',
            'QUEUED', 'QUEUED', 'QUEUED', 'QUEUED',
            'PROCESSING',
            'READY', 'READY', 'READY',
            'COMPLETED', 'COMPLETED', 'COMPLETED', 'COMPLETED',
            'COMPLETED', 'COMPLETED', 'COMPLETED', 'COMPLETED',
        ];

        DB::transaction(function () use ($menus, $cashier, $kitchen, $names, $statuses) {
            $queueSequence = 0;

            foreach ($statuses as $index => $status) {
                $number = $index + 1;
                $orderedAt = now()->subMinutes(150 - ($index * 7));
                $itemCount = ($index % 3) + 1;
                $selectedMenus = collect(range(0, $itemCount - 1))
                    ->map(fn (int $offset) => $menus[($index + $offset) % $menus->count()]);
                $subtotal = 0;
                $processingMinutes = 0;

                foreach ($selectedMenus as $offset => $menu) {
                    $quantity = (($index + $offset) % 2) + 1;
                    $subtotal += (float) $menu->price * $quantity;
                    $processingMinutes += $menu->estimated_prep_minutes * $quantity;
                }

                $isPaid = ! in_array($status, ['PENDING_PAYMENT', 'CANCELLED'], true);
                $order = Order::create([
                    'order_number' => 'ORD-'.now()->format('Ymd').'-D'.str_pad((string) $number, 3, '0', STR_PAD_LEFT),
                    'tracking_token' => hash('sha256', 'atrium-dummy-order-'.$number),
                    'created_by_user_id' => $index % 4 === 0 ? $cashier->id : null,
                    'customer_name' => $names[$index],
                    'customer_phone' => '0812'.str_pad((string) (10000000 + $number), 8, '0', STR_PAD_LEFT),
                    'table_number' => $index % 3 === 0 ? null : 'T'.str_pad((string) (($index % 8) + 1), 2, '0', STR_PAD_LEFT),
                    'order_channel' => $index % 4 === 0 ? 'CASHIER' : 'QR',
                    'order_type' => $index % 3 === 0 ? 'TAKEAWAY' : 'DINE_IN',
                    'subtotal' => $subtotal,
                    'total_amount' => $subtotal,
                    'status' => $status,
                    'payment_status' => $isPaid ? 'PAID' : 'UNPAID',
                    'base_priority' => $status === 'QUEUED' && $index === 4 ? 2 : 3,
                    'estimated_processing_minutes' => max(1, $processingMinutes),
                    'notes' => $index % 6 === 0 ? 'Mohon tanpa gula.' : null,
                    'ordered_at' => $orderedAt,
                    'paid_at' => $isPaid ? $orderedAt->copy()->addMinutes(2) : null,
                    'cancelled_at' => $status === 'CANCELLED' ? $orderedAt->copy()->addMinutes(3) : null,
                    'cancel_reason' => $status === 'CANCELLED' ? 'Pelanggan mengubah pesanan.' : null,
                    'created_at' => $orderedAt,
                    'updated_at' => $orderedAt,
                ]);

                foreach ($selectedMenus as $offset => $menu) {
                    $quantity = (($index + $offset) % 2) + 1;
                    $order->items()->create([
                        'menu_id' => $menu->id,
                        'menu_name_snapshot' => $menu->name,
                        'unit_price' => $menu->price,
                        'quantity' => $quantity,
                        'subtotal' => (float) $menu->price * $quantity,
                        'estimated_prep_minutes' => $menu->estimated_prep_minutes,
                        'notes' => $offset === 0 && $index % 5 === 0 ? 'Es sedikit.' : null,
                        'created_at' => $orderedAt,
                        'updated_at' => $orderedAt,
                    ]);
                }

                DB::table('order_status_histories')->insert([
                    'order_id' => $order->id,
                    'from_status' => null,
                    'to_status' => 'PENDING_PAYMENT',
                    'changed_by_user_id' => $order->created_by_user_id,
                    'created_at' => $orderedAt,
                ]);

                if (! $isPaid) {
                    if ($status === 'CANCELLED') {
                        DB::table('order_status_histories')->insert([
                            'order_id' => $order->id, 'from_status' => 'PENDING_PAYMENT', 'to_status' => 'CANCELLED',
                            'changed_by_user_id' => $cashier->id, 'reason' => $order->cancel_reason, 'created_at' => $order->cancelled_at,
                        ]);
                    }

                    continue;
                }

                Payment::create([
                    'order_id' => $order->id,
                    'payment_number' => 'PAY-DUMMY-'.str_pad((string) $number, 3, '0', STR_PAD_LEFT),
                    'method' => $index % 2 === 0 ? 'CASH' : 'QRIS',
                    'amount' => $subtotal,
                    'status' => 'SUCCESS',
                    'paid_at' => $order->paid_at,
                    'verified_at' => $order->paid_at,
                    'verified_by_user_id' => $cashier->id,
                    'created_at' => $order->paid_at,
                    'updated_at' => $order->paid_at,
                ]);

                $queueSequence++;
                $queuedAt = $order->paid_at->copy()->addMinute();
                $processingAt = in_array($status, ['PROCESSING', 'READY', 'COMPLETED'], true)
                    ? $queuedAt->copy()->addMinutes(8 + ($index % 4)) : null;
                $readyAt = in_array($status, ['READY', 'COMPLETED'], true)
                    ? $processingAt->copy()->addMinutes($order->estimated_processing_minutes) : null;
                $completedAt = $status === 'COMPLETED' ? $readyAt->copy()->addMinutes(2) : null;

                $queue = QueueEntry::create([
                    'order_id' => $order->id,
                    'queue_date' => $queuedAt->toDateString(),
                    'sequence_number' => $queueSequence,
                    'queue_number' => 'A'.str_pad((string) $queueSequence, 3, '0', STR_PAD_LEFT),
                    'base_priority' => $order->base_priority,
                    'status' => match ($status) {
                        'QUEUED' => 'WAITING',
                        default => $status,
                    },
                    'queued_at' => $queuedAt,
                    'claimed_by_user_id' => $processingAt ? $kitchen->id : null,
                    'processing_started_at' => $processingAt,
                    'ready_at' => $readyAt,
                    'completed_at' => $completedAt,
                    'estimated_wait_minutes' => $processingAt ? $queuedAt->diffInMinutes($processingAt) : 10 + ($index % 4) * 4,
                    'created_at' => $queuedAt,
                    'updated_at' => $completedAt ?? $readyAt ?? $processingAt ?? $queuedAt,
                ]);

                $this->insertHistories($order, $queue, $status, $cashier->id, $kitchen->id);
            }

            DB::table('daily_counters')->updateOrInsert(
                ['counter_date' => now()->toDateString(), 'counter_type' => 'QUEUE'],
                ['last_value' => $queueSequence, 'updated_at' => now()],
            );
        });

        $this->command?->info('20 pesanan dummy berhasil dibuat.');
    }

    private function insertHistories(Order $order, QueueEntry $queue, string $status, int $cashierId, int $kitchenId): void
    {
        DB::table('order_status_histories')->insert([
            'order_id' => $order->id, 'from_status' => 'PENDING_PAYMENT', 'to_status' => 'QUEUED',
            'changed_by_user_id' => $cashierId, 'created_at' => $queue->queued_at,
        ]);
        DB::table('queue_histories')->insert([
            'queue_id' => $queue->id, 'from_status' => null, 'to_status' => 'WAITING',
            'changed_by_user_id' => $cashierId, 'created_at' => $queue->queued_at,
        ]);

        if (in_array($status, ['PROCESSING', 'READY', 'COMPLETED'], true)) {
            DB::table('order_status_histories')->insert(['order_id' => $order->id, 'from_status' => 'QUEUED', 'to_status' => 'PROCESSING', 'changed_by_user_id' => $kitchenId, 'created_at' => $queue->processing_started_at]);
            DB::table('queue_histories')->insert(['queue_id' => $queue->id, 'from_status' => 'WAITING', 'to_status' => 'PROCESSING', 'changed_by_user_id' => $kitchenId, 'created_at' => $queue->processing_started_at]);
        }
        if (in_array($status, ['READY', 'COMPLETED'], true)) {
            DB::table('order_status_histories')->insert(['order_id' => $order->id, 'from_status' => 'PROCESSING', 'to_status' => 'READY', 'changed_by_user_id' => $kitchenId, 'created_at' => $queue->ready_at]);
            DB::table('queue_histories')->insert(['queue_id' => $queue->id, 'from_status' => 'PROCESSING', 'to_status' => 'READY', 'changed_by_user_id' => $kitchenId, 'created_at' => $queue->ready_at]);
        }
        if ($status === 'COMPLETED') {
            DB::table('order_status_histories')->insert(['order_id' => $order->id, 'from_status' => 'READY', 'to_status' => 'COMPLETED', 'changed_by_user_id' => $cashierId, 'created_at' => $queue->completed_at]);
            DB::table('queue_histories')->insert(['queue_id' => $queue->id, 'from_status' => 'READY', 'to_status' => 'COMPLETED', 'changed_by_user_id' => $cashierId, 'created_at' => $queue->completed_at]);
        }
    }
}
