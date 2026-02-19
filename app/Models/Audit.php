<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Audit extends Model
{
    protected $fillable = [
        'audit_number',
        'outlet_id',
        'product_id',
        'audited_by',
        'system_quantity',
        'physical_quantity',
        'reason',
        'notes',
        'audited_at',
    ];

    protected $casts = [
        'audited_at'      => 'datetime',
        'system_quantity' => 'integer',
        'physical_quantity' => 'integer',
    ];

    // Relationships
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function auditedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'audited_by');
    }

    // Accessor - difference dihitung manual jika tidak pakai stored column
    public function getDifferenceAttribute(): int
    {
        return $this->physical_quantity - $this->system_quantity;
    }

    public function hasSurplus(): bool
    {
        return $this->difference > 0;
    }

    public function hasDeficit(): bool
    {
        return $this->difference < 0;
    }
}