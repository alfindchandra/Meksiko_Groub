<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $fillable = [
        'product_id',
        'outlet_id',
        'quantity',
        'reserved',
    ];

    protected $appends = ['is_low_stock'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    // Accessors
    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity <= $this->product->min_stock;
    }

    public function getAvailableAttribute(): int
    {
        return $this->quantity - $this->reserved;
    }

    // Helper methods
    public function canReserve(int $amount): bool
    {
        return $this->available >= $amount;
    }

    public function reserve(int $amount): void
    {
        if (!$this->canReserve($amount)) {
            throw new \Exception("Insufficient available stock");
        }
        
        $this->increment('reserved', $amount);
    }

    public function releaseReservation(int $amount): void
    {
        $this->decrement('reserved', min($amount, $this->reserved));
    }
}