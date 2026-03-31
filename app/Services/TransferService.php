<?php

namespace App\Services;

use App\Models\StockTransfer;
use App\Models\Stock;
use App\Models\StockHistory;
use App\Models\Shipment;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferService
{
    public function approveTransfer(StockTransfer $transfer, int $approverId): StockTransfer
    {
        return DB::transaction(function () use ($transfer, $approverId) {
            if (!$transfer->canBeApproved()) {
                throw new \Exception('Transfer tidak dapat disetujui. Status saat ini: ' . $transfer->status);
            }

            $transfer->update([
                'status' => 'approved',
                'approved_by' => $approverId,
                'approved_at' => now(),
            ]);

            // Create notification for requester
            try {
                $this->createNotification(
                    $transfer->requested_by,
                    'transfer_approved',
                    "Transfer {$transfer->transfer_number} telah disetujui",
                    $transfer->id
                );
            } catch (\Exception $e) {
                Log::warning('Failed to create notification: ' . $e->getMessage());
            }

            return $transfer->fresh();
        });
    }

    public function rejectTransfer(StockTransfer $transfer, int $rejectorId, string $reason): StockTransfer
    {
        return DB::transaction(function () use ($transfer, $rejectorId, $reason) {
            if (!$transfer->canBeApproved()) {
                throw new \Exception('Transfer tidak dapat ditolak. Status saat ini: ' . $transfer->status);
            }

            // Release reserved stock
            foreach ($transfer->items as $item) {
                $stock = Stock::where('product_id', $item->product_id)
                    ->where('outlet_id', $transfer->from_outlet_id)
                    ->first();
                
                if ($stock && $stock->reserved > 0) {
                    $stock->releaseReservation($item->quantity_requested);
                }
            }

            $transfer->update([
                'status' => 'rejected',
                'approved_by' => $rejectorId,
                'rejection_reason' => $reason,
                'approved_at' => now(),
            ]);

            // Create notification
            try {
                $this->createNotification(
                    $transfer->requested_by,
                    'transfer_rejected',
                    "Transfer {$transfer->transfer_number} ditolak: {$reason}",
                    $transfer->id
                );
            } catch (\Exception $e) {
                Log::warning('Failed to create notification: ' . $e->getMessage());
            }

            return $transfer->fresh();
        });
    }

    public function sendTransfer(
    StockTransfer $transfer,
    int $senderId,
    array $itemQuantities,
    ?string $courierName = null,
    ?string $vehicleNumber = null,
    ?string $notes = null
): StockTransfer {
    return DB::transaction(function () use (
        $transfer, $senderId, $itemQuantities, 
        $courierName, $vehicleNumber, $notes
    ) {
        if (!$transfer->canBeSent()) {
            throw new \Exception('Transfer tidak dapat dikirim. Status: ' . $transfer->status);
        }

        foreach ($transfer->items as $item) {
            $quantitySent = $itemQuantities[$item->id] ?? $item->quantity_requested;
            $item->update(['quantity_sent' => $quantitySent]);

            $stock = Stock::where('product_id', $item->product_id)
                ->where('outlet_id', $transfer->from_outlet_id)
                ->firstOrFail();

            // Validate stock
            if ($stock->quantity < $quantitySent) {
                throw new \Exception(
                    "Stok tidak cukup untuk produk: {$item->product->name}. " .
                    "Tersedia: {$stock->quantity}, Diminta: {$quantitySent}"
                );
            }

            // Get stock before any changes (total physical stock before reservation release)
            $quantityBefore = $stock->quantity + $stock->reserved;

            // Release reservation first
            $stock->releaseReservation($item->quantity_requested);

            // Deduct actual stock
            $stock->decrement('quantity', $quantitySent);

            // Record history
            StockHistory::create([
                'product_id' => $item->product_id,
                'outlet_id' => $transfer->from_outlet_id,
                'type' => 'transfer_out',
                'quantity_before' => $quantityBefore,
                'quantity_change' => $quantitySent,
                'quantity_after' => $stock->fresh()->quantity,
                'reference_type' => 'StockTransfer',
                'reference_id' => $transfer->id,
                'user_id' => $senderId,
                'notes' => "Transfer ke {$transfer->toOutlet->name}",
            ]);
        }

        // Create shipment with courier info
        $shipmentNumber = 'SHP-' . date('Ymd') . '-' . str_pad(
            Shipment::whereDate('created_at', today())->count() + 1,
            3, '0', STR_PAD_LEFT
        );

        Shipment::create([
            'shipment_number' => $shipmentNumber,
            'stock_transfer_id' => $transfer->id,
            'type' => 'internal_transfer',
            'from_outlet_id' => $transfer->from_outlet_id,
            'to_outlet_id' => $transfer->to_outlet_id,
            'status' => 'on_the_way',
            'courier_name' => $courierName,
            'vehicle_number' => $vehicleNumber,
            'notes' => $notes,
            'shipped_at' => now(),
        ]);

        $transfer->update([
            'status' => 'in_transit',
            'sent_by' => $senderId,
            'sent_at' => now(),
        ]);

        // Notify receiver
        try {
            $receiverUsers = $transfer->toOutlet->users()->get();
            foreach ($receiverUsers as $user) {
                $this->createNotification(
                    $user->id,
                    'transfer_in_transit',
                    "Transfer {$transfer->transfer_number} sedang dalam perjalanan",
                    $transfer->id
                );
            }
        } catch (\Exception $e) {
            Log::warning('Notification failed: ' . $e->getMessage());
        }

        return $transfer->fresh();
    });
}

    public function receiveTransfer(StockTransfer $transfer, array $receivedItems, int $receiverId, ?string $notes = null): StockTransfer
    {
        return DB::transaction(function () use ($transfer, $receivedItems, $receiverId, $notes) {
            if (!$transfer->canBeReceived()) {
                throw new \Exception('Transfer tidak dapat diterima. Status saat ini: ' . $transfer->status);
            }

            foreach ($transfer->items as $item) {
                $quantityReceived = $receivedItems[$item->id]['quantity_received'];
                $itemNotes = $receivedItems[$item->id]['notes'] ?? null;

                $item->update([
                    'quantity_received' => $quantityReceived,
                    'notes' => $itemNotes,
                ]);

                // Add stock to receiver outlet
                $stock = Stock::firstOrCreate(
                    [
                        'product_id' => $item->product_id,
                        'outlet_id' => $transfer->to_outlet_id,
                    ],
                    [
                        'quantity' => 0,
                        'reserved' => 0,
                    ]
                );

                $stock->increment('quantity', $quantityReceived);

                // Record history
                StockHistory::create([
                    'product_id' => $item->product_id,
                    'outlet_id' => $transfer->to_outlet_id,
                    'type' => 'transfer_in',
                    'quantity_before' => $stock->quantity - $quantityReceived,
                    'quantity_change' => $quantityReceived,
                    'quantity_after' => $stock->quantity,
                    'reference_type' => 'StockTransfer',
                    'reference_id' => $transfer->id,
                    'user_id' => $receiverId,
                    'notes' => "Transfer dari {$transfer->fromOutlet->name}",
                ]);
            }

            // Update shipment
            if ($transfer->shipment) {
                $transfer->shipment->update([
                    'status' => 'delivered',
                    'delivered_at' => now(),
                ]);
            }

            $transfer->update([
                'status' => 'received',
                'received_by' => $receiverId,
                'received_at' => now(),
                'notes' => $notes ? $transfer->notes . "\n\nCatatan Penerimaan: " . $notes : $transfer->notes,
            ]);

            // Notify sender
            try {
                $this->createNotification(
                    $transfer->requested_by,
                    'transfer_received',
                    "Transfer {$transfer->transfer_number} telah diterima",
                    $transfer->id
                );
            } catch (\Exception $e) {
                Log::warning('Failed to create notification: ' . $e->getMessage());
            }

            return $transfer->fresh();
        });
    }

    private function createNotification($userId, $type, $message, $referenceId)
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => 'Update Transfer',
                'message' => $message,
                'reference_type' => 'StockTransfer',
                'reference_id' => $referenceId,
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            // Don't throw - notifications are not critical
        }
    }
    
}