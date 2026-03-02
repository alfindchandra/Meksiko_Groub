<?php

namespace App\Livewire\MeksikoClean;

use App\Models\McTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updateStatus($transactionId, $newStatus)
    {
        $transaction = McTransaction::findOrFail($transactionId);

        if ($newStatus === 'diambil' && $transaction->payment_status === 'unpaid') {
            session()->flash('error', "Pesanan {$transaction->transaction_number} belum lunas. Selesaikan pembayaran terlebih dahulu sebelum barang diambil.");
            return;
        }

        $transaction->update(['status' => $newStatus]);
        
        session()->flash('message', "Status transaksi {$transaction->transaction_number} berhasil diubah ke {$newStatus}.");
    }

    public function markAsPaid($transactionId)
    {
        $transaction = McTransaction::findOrFail($transactionId);

        if ($transaction->payment_status === 'unpaid') {
            $transaction->update(['payment_status' => 'paid']);
            session()->flash('message', "Pembayaran untuk pesanan {$transaction->transaction_number} telah ditandai LUNAS.");
        }
    }

    public function render()
    {
        $transactions = McTransaction::with(['partner', 'items'])
            ->when($this->search, function ($query) {
                $query->where('transaction_number', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.meksiko-clean.transaction-list', [
            'transactions' => $transactions
        ])->layout('layouts.app');
    }
}
