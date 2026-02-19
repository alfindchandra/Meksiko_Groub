<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StockTransfer extends Model
{
    protected $fillable = [
        'transfer_number',
        'from_outlet_id',
        'to_outlet_id',
        'requested_by',
        'approved_by',
        'sent_by',
        'received_by',
        'status',
        'notes',
        'rejection_reason',
        'approved_at',
        'sent_at',
        'received_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'sent_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    // Relationships
    public function fromOutlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'from_outlet_id');
    }

    public function toOutlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'to_outlet_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransferItem::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    // Status check helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isInTransit(): bool
    {
        return $this->status === 'in_transit';
    }

    public function canBeApproved(): bool
    {
        return $this->isPending();
    }

    public function canBeSent(): bool
    {
        return $this->isApproved();
    }

    public function canBeReceived(): bool
    {
        return $this->isInTransit();
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
    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
    
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}