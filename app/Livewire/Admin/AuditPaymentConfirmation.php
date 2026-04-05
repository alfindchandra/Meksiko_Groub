<?php

namespace App\Livewire\Admin;

use App\Models\Audit;
use App\Services\StockService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuditPaymentConfirmation extends Component
{
    public function mount()
    {
        // Must be admin to access this
    }

    public function confirmAllFromOutlet($outletId)
    {
        try {
            DB::beginTransaction();
            $stockService = app(StockService::class);

            $auditsToConfirm = Audit::with('product')
                ->where('outlet_id', $outletId)
                ->where('status', 'payment_submitted')
                ->get();

            $auditCount = 0;
            foreach ($auditsToConfirm as $item) {
                // Determine adjustment
                $adjustmentType = $item->difference > 0 ? 'in' : 'out';
                $adjustmentQuantity = abs($item->difference);

                // Adjust stock based on audit since it's confirmed now
                $stockService->adjustStock(
                    $item->product_id,
                    $outletId,
                    $adjustmentQuantity,
                    $adjustmentType,
                    auth()->id(),
                    "Audit Confirmed {$item->audit_number}: {$item->reason}"
                );

                $item->update([
                    'status' => 'confirmed'
                ]);

                $auditCount++;
            }

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Pembayaran dikonfirmasi! {$auditCount} stok produk telah disesuaikan."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Audit Confirm Error: ' . $e->getMessage());
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal mengkonfirmasi audit: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        // Get grouped audits that are payment_submitted
        $submittedAudits = Audit::with(['outlet', 'product'])
            ->where('status', 'payment_submitted')
            ->orderBy('audited_at', 'desc')
            ->get();

        // Group by outlet for easy confirmation
        $groupedAudits = $submittedAudits->groupBy('outlet_id');

        return view('livewire.admin.audit-payment-confirmation', [
            'groupedAudits' => $groupedAudits
        ])->layout('layouts.app');
    }
}
