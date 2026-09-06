<?php

namespace App\Services;

use App\Jobs\ProcessNotificationOutbox;
use App\Models\NotificationOutbox;
use App\Models\Order;

class NotificationOutboxService
{
    public function enqueue(Order $order, string $eventType, array $payload): NotificationOutbox
    {
        $outbox = NotificationOutbox::firstOrCreate(
            ['event_key' => "order:{$order->id}:{$eventType}"],
            ['order_id' => $order->id, 'event_type' => $eventType, 'channel' => 'WEB', 'payload' => $payload, 'status' => 'PENDING'],
        );
        if ($outbox->wasRecentlyCreated) {
            ProcessNotificationOutbox::dispatch($outbox->id)->afterCommit();
        }

        return $outbox;
    }
}
