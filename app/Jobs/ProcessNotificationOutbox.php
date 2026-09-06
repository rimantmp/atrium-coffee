<?php

namespace App\Jobs;

use App\Models\NotificationOutbox;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessNotificationOutbox implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public int $tries = 4;

    public int $timeout = 30;

    public array $backoff = [5, 30, 120];

    public function __construct(public readonly int $outboxId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function (): void {
            $outbox = NotificationOutbox::query()->lockForUpdate()->findOrFail($this->outboxId);
            if ($outbox->status === 'SENT') {
                return;
            }
            $outbox->increment('attempts');
            Log::info('notification.outbox.sent', ['outbox_id' => $outbox->id, 'order_id' => $outbox->order_id, 'event_type' => $outbox->event_type, 'channel' => $outbox->channel]);
            $outbox->update(['status' => 'SENT', 'processed_at' => now(), 'last_error' => null]);
        });
    }

    public function uniqueId(): string
    {
        return (string) $this->outboxId;
    }

    public function failed(?Throwable $exception): void
    {
        NotificationOutbox::query()->whereKey($this->outboxId)->update(['status' => 'FAILED', 'last_error' => mb_substr($exception?->getMessage() ?? 'Unknown error', 0, 2000)]);
    }
}
