<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDiscountTier extends Model
{
    protected $fillable = [
        'product_id',
        'min_quantity',
        'discount_percentage',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get applicable discount for quantity
    public static function getDiscountForQuantity(int $productId, int $quantity): float
    {
        return self::where('product_id', $productId)
            ->where('is_active', true)
            ->where('min_quantity', '<=', $quantity)
            ->orderBy('min_quantity', 'desc')
            ->value('discount_percentage') ?? 0;
    }
}