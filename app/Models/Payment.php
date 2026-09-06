<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['order_id', 'payment_number', 'method', 'amount', 'status', 'paid_at', 'verified_at', 'verified_by_user_id'];

    protected $casts = ['amount' => 'decimal:2', 'paid_at' => 'datetime', 'verified_at' => 'datetime'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
