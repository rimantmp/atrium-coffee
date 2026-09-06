<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'tracking_token', 'checkout_token', 'created_by_user_id', 'customer_name', 'customer_phone',
        'table_number', 'order_channel', 'order_type', 'subtotal', 'total_amount', 'status',
        'payment_status', 'base_priority', 'estimated_processing_minutes', 'notes', 'ordered_at',
        'paid_at', 'cancelled_at', 'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class, 'payment_status' => PaymentStatus::class,
            'subtotal' => 'decimal:2', 'total_amount' => 'decimal:2',
            'ordered_at' => 'datetime', 'paid_at' => 'datetime', 'cancelled_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function queue(): HasOne
    {
        return $this->hasOne(QueueEntry::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
