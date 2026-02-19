<?php

namespace App\Livewire\Transfer;

use App\Models\StockTransfer;
use App\Models\Stock;
use App\Services\TransferService;
use Livewire\Component;

class SendTransfer extends Component
{
    public StockTransfer $transfer;
    public $showConfirmModal = false;
    public $courierName = '';
    public $vehicleNumber = '';
    public $notes = '';
    public $itemQuantities = [];

    protected $rules = [
        'courierName' => 'nullable|string|max:100',
        'vehicleNumber' => 'nullable|string|max:30',
        'notes' => 'nullable|string|max:500',
        'itemQuantities.*.quantity_sent' => 'required|integer|min:0',
    ];

    protected $messages = [
        'itemQuantities.*.quantity_sent.required' => 'Jumlah kirim wajib diisi',
        'itemQuantities.*.quantity_sent.min' => 'Jumlah tidak boleh negatif',
    ];

    public function mount($transferId)
    {
        $this->transfer = StockTransfer::with([
            'fromOutlet',
            'toOutlet',
            'requestedBy',
            'items.product',
        ])->findOrFail($transferId);

        // Authorize
        if (!auth()->user()->can('send', $this->transfer)) {
            abort(403, 'Anda tidak memiliki akses untuk mengirim transfer ini.');
        }

        if (!$this->transfer->canBeSent()) {
            abort(403, 'Transfer tidak dapat dikirim. Status: ' . $this->transfer->status);
        }

        // Initialize item quantities from request
        foreach ($this->transfer->items as $item) {
            $stock = Stock::where('product_id', $item->product_id)
                ->where('outlet_id', $this->transfer->from_outlet_id)
                ->first();

            $this->itemQuantities[$item->id] = [
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'product_sku' => $item->product->sku,
                'unit' => $item->product->unit,
                'quantity_requested' => $item->quantity_requested,
                'available_stock' => $stock ? $stock->available : 0,
                'quantity_sent' => $item->quantity_requested, // Default same as requested
            ];
        }
    }

    public function openConfirmModal()
    {
        // Validate before opening modal
        $hasError = false;
        foreach ($this->itemQuantities as $itemId => $item) {
            if ($item['quantity_sent'] > $item['available_stock']) {
                $this->addError("itemQuantities.{$itemId}.quantity_sent",
                    "Jumlah kirim melebihi stok tersedia ({$item['available_stock']})");
                $hasError = true;
            }
            if ($item['quantity_sent'] < 0) {
                $this->addError("itemQuantities.{$itemId}.quantity_sent",
                    "Jumlah kirim tidak boleh negatif");
                $hasError = true;
            }
        }

        if ($hasError) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Periksa kembali jumlah yang akan dikirim'
            ]);
            return;
        }

        $this->showConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
    }

    public function confirmSend()
    {
        try {
            $transferService = app(TransferService::class);

            // Prepare item quantities for service
            $quantities = [];
            foreach ($this->itemQuantities as $itemId => $item) {
                $quantities[$itemId] = $item['quantity_sent'];
            }

            $transferService->sendTransfer(
                $this->transfer,
                auth()->id(),
                $quantities,
                $this->courierName,
                $this->vehicleNumber,
                $this->notes
            );

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Transfer {$this->transfer->transfer_number} berhasil dikirim!"
            ]);

            return redirect()->route('transfer.detail', $this->transfer->id);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal mengirim transfer: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transfer.send-transfer')->layout('layouts.app');
    }
}