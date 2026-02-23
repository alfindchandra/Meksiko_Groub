<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PawnPayment extends Model
{
    protected $fillable = [
        'pawn_transaction_id', 'user_id',
        'amount', 'payment_type', 'payment_method', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function pawnTransaction(): BelongsTo
    {
        return $this->belongsTo(PawnTransaction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}