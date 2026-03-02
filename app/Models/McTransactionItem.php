<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McTransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'service_id',
        'item_name',
        'qty',
        'price',
        'subtotal',
    ];

    public function transaction()
    {
        return $this->belongsTo(McTransaction::class, 'transaction_id');
    }

    public function service()
    {
        return $this->belongsTo(McService::class, 'service_id');
    }
}
