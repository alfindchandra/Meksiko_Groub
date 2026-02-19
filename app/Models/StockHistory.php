<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockHistory extends Model
{
    protected $fillable = [
        'product_id',
        'outlet_id',
        'type',
        'quantity_before',
        'quantity_change',
        'quantity_after',
        'reference_type',
        'reference_id',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic relationship for reference
    public function reference()
    {
        return $this->morphTo('reference', 'reference_type', 'reference_id');
    }

    // Scopes
    public function scopeByOutlet($query, int $outletId)
    {
        return $query->where('outlet_id', $outletId);
    }

    public function scopeByProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'in' => 'Stock In',
            'out' => 'Stock Out',
            'adjustment' => 'Adjustment',
            'transfer_in' => 'Transfer In',
            'transfer_out' => 'Transfer Out',
            default => ucfirst($this->type),
        };
    }

    public function getTypeBadgeColorAttribute(): string
    {
        return match($this->type) {
            'in', 'transfer_in' => 'success',
            'out', 'transfer_out' => 'danger',
            'adjustment' => 'warning',
            default => 'info',
        };
    }
}