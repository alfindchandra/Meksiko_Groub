<?php

namespace App\Livewire\Transfer;

use App\Models\StockTransfer;
use App\Services\TransferService;
use Livewire\Component;

class ReceiveTransfer extends Component
{
    public $transfer;
    public $receivedItems = [];
    public $showConfirmModal = false;
    public $notes = '';

    protected $listeners = ['confirmReceive'];

    public function mount($transfer)
    {
        $this->transfer = StockTransfer::with(['items.product', 'fromOutlet', 'toOutlet'])
            ->findOrFail($transfer);

        // Authorize
        if (!auth()->user()->can('receive', $this->transfer)) {
            abort(403);
        }

        // Initialize received items
        foreach ($this->transfer->items as $item) {
            $this->receivedItems[$item->id] = [
                'quantity_received' => $item->quantity_sent ?? $item->quantity_requested,
                'notes' => '',
            ];
        }
    }

    public function openConfirmModal()
    {
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
    }

    public function confirmReceive()
    {
        try {
            $transferService = app(TransferService::class);
            
            $transferService->receiveTransfer(
                $this->transfer,
                $this->receivedItems,
                auth()->id(),
                $this->notes
            );

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Transfer berhasil diterima!'
            ]);

            return redirect()->route('transfer.detail', $this->transfer->id);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal menerima transfer: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transfer.receive-transfer')->layout('layouts.app');
    }
}