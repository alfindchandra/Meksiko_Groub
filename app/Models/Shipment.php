<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'shipment_number',
        'stock_transfer_id',
        'type',
        'from_outlet_id',
        'to_outlet_id',
        'status',
        'courier_name',
        'vehicle_number',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relationships
    public function stockTransfer(): BelongsTo
    {
        return $this->belongsTo(StockTransfer::class);
    }

    public function fromOutlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'from_outlet_id');
    }

    public function toOutlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'to_outlet_id');
    }

    // Status Checks
    public function isPrepared(): bool
    {
        return $this->status === 'prepared';
    }

    public function isOnTheWay(): bool
    {
        return $this->status === 'on_the_way';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForOutlet($query, int $outletId)
    {
        return $query->where(function($q) use ($outletId) {
            $q->where('from_outlet_id', $outletId)
              ->orWhere('to_outlet_id', $outletId);
        });
    }

    public function scopeInTransit($query)
    {
        return $query->whereIn('status', ['prepared', 'on_the_way']);
    }

    // Accessors
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'prepared' => 'Prepared',
            'on_the_way' => 'On The Way',
            'delivered' => 'Delivered',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'prepared' => 'warning',
            'on_the_way' => 'info',
            'delivered' => 'success',
            default => 'secondary',
        };
    }

    public function getProgressPercentageAttribute(): int
    {
        return match($this->status) {
            'prepared' => 33,
            'on_the_way' => 66,
            'delivered' => 100,
            default => 0,
        };
    }
}