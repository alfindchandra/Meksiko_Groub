<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PawnTransaction extends Model
{
    protected $fillable = [
        'pawn_number', 'outlet_id', 'user_id',
        'customer_name', 'customer_id_number', 'customer_phone', 'customer_address',
        'item_name', 'item_category', 'item_description', 'item_weight', 'item_photos',
        'appraisal_value', 'loan_amount', 'admin_fee', 'interest_rate', 'loan_period_days',
        'status', 'start_date', 'due_date', 'redeemed_at',
        'total_interest', 'total_payment', 'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'redeemed_at' => 'date',
        'appraisal_value' => 'decimal:2',
        'loan_amount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'total_interest' => 'decimal:2',
        'total_payment' => 'decimal:2',
        'item_photos' => 'array',
    ];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function extensions(): HasMany
    {
        return $this->hasMany(PawnExtension::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PawnPayment::class);
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'active' && $this->due_date < now();
    }

    public function getDaysOverdueAttribute(): int
    {
        if (!$this->isOverdue()) return 0;
        return now()->diffInDays($this->due_date);
    }

    public function calculateInterest(): float
    {
        $days = now()->diffInDays($this->start_date);
        $months = ceil($days / 30);
        return (float)$this->loan_amount * ((float)$this->interest_rate / 100) * $months;
    }

    public function getTotalRedemptionAmount(): float
    {
        return (float)$this->loan_amount + $this->calculateInterest();
    }
}