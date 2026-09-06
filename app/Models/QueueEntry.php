<?php

namespace App\Models;

use App\Enums\QueueStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueEntry extends Model
{
    protected $table = 'queues';

    protected $fillable = ['order_id', 'queue_date', 'sequence_number', 'queue_number', 'base_priority', 'status', 'queued_at', 'claimed_by_user_id', 'processing_started_at', 'ready_at', 'completed_at', 'cancelled_at', 'estimated_wait_minutes'];

    protected function casts(): array
    {
        return ['status' => QueueStatus::class, 'queue_date' => 'date', 'queued_at' => 'datetime', 'processing_started_at' => 'datetime', 'ready_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function claimedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimed_by_user_id');
    }
}
