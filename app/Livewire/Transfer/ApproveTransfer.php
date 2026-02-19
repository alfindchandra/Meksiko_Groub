<?php

namespace App\Livewire\Transfer;

use App\Models\StockTransfer;
use App\Services\TransferService;
use Livewire\Component;

class ApproveTransfer extends Component
{
    public $transfer;
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $rejectionReason = '';

    protected $rules = [
        'rejectionReason' => 'required|string|min:10|max:500',
    ];

    protected $messages = [
        'rejectionReason.required' => 'Alasan penolakan wajib diisi',
        'rejectionReason.min' => 'Alasan minimal 10 karakter',
    ];

    public function mount($transfer)
    {
        $this->transfer = StockTransfer::with([
            'fromOutlet', 
            'toOutlet', 
            'requestedBy',
            'items.product'
        ])->findOrFail($transfer);

        // Authorize
        if (!auth()->user()->can('approve', $this->transfer)) {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui transfer ini.');
        }

        if (!$this->transfer->canBeApproved()) {
            abort(403, 'Transfer tidak dapat disetujui.');
        }
    }

    public function openApproveModal()
    {
        $this->showApproveModal = true;
    }

    public function closeApproveModal()
    {
        $this->showApproveModal = false;
    }

    public function openRejectModal()
    {
        $this->showRejectModal = true;
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->rejectionReason = '';
        $this->resetValidation();
    }

    public function approve()
    {
        try {
            $transferService = app(TransferService::class);
            
            $transferService->approveTransfer(
                $this->transfer,
                auth()->id()
            );

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Transfer {$this->transfer->transfer_number} berhasil disetujui!"
            ]);

            return redirect()->route('transfer.detail', $this->transfer->id);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal menyetujui transfer: ' . $e->getMessage()
            ]);
        }
    }

    public function reject()
    {
        $this->validate();

        try {
            $transferService = app(TransferService::class);
            
            $transferService->rejectTransfer(
                $this->transfer,
                auth()->id(),
                $this->rejectionReason
            );

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Transfer {$this->transfer->transfer_number} ditolak."
            ]);

            return redirect()->route('transfer.list');

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal menolak transfer: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transfer.approve-transfer')->layout('layouts.app');
    }
}