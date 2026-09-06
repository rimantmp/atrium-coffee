<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\QueueEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ReportService
{
    public function period(string $from, string $to): array
    {
        [$start, $end] = $this->bounds($from, $to);
        $paid = Order::query()->where('payment_status', 'PAID')->whereBetween('ordered_at', [$start, $end]);
        $count = (clone $paid)->count();
        $queues = QueueEntry::query()->whereHas('order', fn (Builder $query) => $query->whereBetween('ordered_at', [$start, $end]));

        return [
            'stats' => [
                'revenue' => (float) (clone $paid)->sum('total_amount'),
                'orders' => $count,
                'average_order' => $count ? (float) (clone $paid)->avg('total_amount') : 0,
                'average_wait' => (float) ((clone $queues)->whereNotNull('processing_started_at')->get()->avg(fn (QueueEntry $queue) => $queue->queued_at->diffInMinutes($queue->processing_started_at)) ?? 0),
                'waiting' => (clone $queues)->where('status', 'WAITING')->count(),
                'completed' => (clone $queues)->where('status', 'COMPLETED')->count(),
            ],
            'topMenus' => OrderItem::query()->selectRaw('menu_name_snapshot, SUM(quantity) as total_quantity, SUM(order_items.subtotal) as total_sales')->whereHas('order', fn (Builder $query) => $query->where('payment_status', 'PAID')->whereBetween('ordered_at', [$start, $end]))->groupBy('menu_name_snapshot')->orderByDesc('total_quantity')->limit(10)->get(),
            'recent' => (clone $paid)->latest('ordered_at')->latest('id')->limit(20)->get(),
        ];
    }

    public function exportRows(string $from, string $to): iterable
    {
        [$start, $end] = $this->bounds($from, $to);

        return Order::query()->with(['queue', 'payments'])->where('payment_status', 'PAID')->whereBetween('ordered_at', [$start, $end])->orderBy('id')->lazyById(200);
    }

    private function bounds(string $from, string $to): array
    {
        $timezone = config('app.timezone');

        return [Carbon::createFromFormat('Y-m-d', $from, $timezone)->startOfDay(), Carbon::createFromFormat('Y-m-d', $to, $timezone)->endOfDay()];
    }
}
