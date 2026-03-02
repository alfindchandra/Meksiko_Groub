<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McTransaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'customer_name',
        'customer_phone',
        'order_type',
        'partner_id',
        'status',
        'total_amount',
        'payment_status',
    ];

    public function partner()
    {
        return $this->belongsTo(McPartner::class, 'partner_id');
    }

    public function items()
    {
        return $this->hasMany(McTransactionItem::class, 'transaction_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_number)) {
                $lastNumber = static::whereDate('created_at', today())->count() + 1;
                $transaction->transaction_number = 'MC-' . date('Ymd') . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
