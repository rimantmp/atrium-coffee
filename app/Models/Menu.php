<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = ['category_id', 'sku', 'name', 'description', 'price', 'estimated_prep_minutes', 'image_path', 'is_available', 'is_active'];

    protected $casts = ['price' => 'decimal:2', 'is_available' => 'boolean', 'is_active' => 'boolean'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
