<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PawnExtension extends Model
{
    protected $fillable = [
        'pawn_transaction_id',
        'user_id',
        'extension_days',
        'extension_fee',
        'new_due_date',
    ];

    protected $casts = [
        'new_due_date' => 'date',
    ];

    public function pawnTransaction()
    {
        return $this->belongsTo(PawnTransaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
