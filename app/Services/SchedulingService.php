<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\QueueStatus;
use App\Models\QueueEntry;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SchedulingService
{
    public function effectivePriority(QueueEntry $queue, ?Carbon $at = null): int
    {
        $at ??= now();
        $interval = (int) $this->setting('aging_interval_minutes', 10);
        $minimum = (int) $this->setting('minimum_priority', 1);
        $waitingMinutes = max(0, (int) $queue->queued_at->diffInMinutes($at));

        return max($minimum, $queue->base_priority - intdiv($waitingMinutes, max(1, $interval)));
    }

    public function orderedWaiting()
    {
        return QueueEntry::query()->with('order.items')->where('status', QueueStatus::Waiting->value)->get()
            ->sortBy(fn (QueueEntry $queue) => sprintf('%02d|%s|%020d', $this->effectivePriority($queue), $queue->queued_at->format('Y-m-d H:i:s.u'), $queue->id))->values();
    }

    public function claimNext(int $userId): ?QueueEntry
    {
        return DB::transaction(function () use ($userId) {
            $candidates = QueueEntry::query()->where('status', QueueStatus::Waiting->value)->lockForUpdate()->get();
            $queue = $candidates->sortBy(fn (QueueEntry $item) => sprintf('%02d|%s|%020d', $this->effectivePriority($item), $item->queued_at->format('Y-m-d H:i:s.u'), $item->id))->first();
            if (! $queue) {
                return null;
            }

            $queue->update(['status' => QueueStatus::Processing, 'claimed_by_user_id' => $userId, 'processing_started_at' => now()]);
            $queue->order()->update(['status' => OrderStatus::Processing]);
            DB::table('queue_histories')->insert(['queue_id' => $queue->id, 'from_status' => 'WAITING', 'to_status' => 'PROCESSING', 'changed_by_user_id' => $userId, 'created_at' => now()]);
            DB::table('order_status_histories')->insert(['order_id' => $queue->order_id, 'from_status' => 'QUEUED', 'to_status' => 'PROCESSING', 'changed_by_user_id' => $userId, 'created_at' => now()]);

            return $queue->load('order.items');
        }, 3);
    }

    private function setting(string $key, mixed $default): mixed
    {
        $row = DB::table('system_settings')->where('key', $key)->value('value');
        if ($row === null) {
            return $default;
        }

        return json_decode($row, true) ?? $row;
    }
}
