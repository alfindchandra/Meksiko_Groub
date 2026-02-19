<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferItem extends Model
{
    protected $fillable = [
        'stock_transfer_id',
        'product_id',
        'quantity_requested',
        'quantity_sent',
        'quantity_received',
        'notes',
    ];

    public function stockTransfer(): BelongsTo
    {
        return $this->belongsTo(StockTransfer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Helper
    public function hasDiscrepancy(): bool
    {
        return $this->quantity_sent !== $this->quantity_received;
    }
}