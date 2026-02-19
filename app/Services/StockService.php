<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    public function adjustStock(
        int $productId,
        int $outletId,
        int $quantity,
        string $type,
        int $userId,
        ?string $notes = null
    ): Stock {
        return DB::transaction(function () use ($productId, $outletId, $quantity, $type, $userId, $notes) {

            // Get or create stock
            $stock = Stock::firstOrCreate(
                [
                    'product_id' => $productId,
                    'outlet_id'  => $outletId,
                ],
                [
                    'quantity' => 0,
                    'reserved' => 0,
                ]
            );

            $quantityBefore = $stock->quantity;

            // Apply adjustment
            if ($type === 'out') {
                if ($stock->quantity < $quantity) {
                    throw new \Exception("Stok tidak mencukupi. Tersedia: {$stock->quantity}, Diminta: {$quantity}");
                }
                $stock->decrement('quantity', $quantity);
            } else {
                // 'in' or 'adjustment'
                $stock->increment('quantity', $quantity);
            }

            $stock->refresh();

            // Record history
            StockHistory::create([
                'product_id'      => $productId,
                'outlet_id'       => $outletId,
                'type'            => $type,
                'quantity_before' => $quantityBefore,
                'quantity_change' => $quantity,
                'quantity_after'  => $stock->quantity,
                'user_id'         => $userId,
                'notes'           => $notes,
            ]);

            return $stock;
        });
    }
}