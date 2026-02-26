<?php

namespace App\Livewire\Pegadaian;

use App\Models\PawnTransaction;
use App\Models\PawnPayment;
use App\Models\PawnExtension;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PawnDetail extends Component
{
    public PawnTransaction $pawn;
    
    // Redemption modal
    public $redemptionAmount = 0;
    public $paymentMethod = 'cash';
    public $redemptionNotes = '';

    // Extension modal
    public $extensionDays = 30;
    public $extensionFee = 0;

    public function mount($pawnId)
    {
        $this->pawn = PawnTransaction::with([
            'outlet', 'user', 'extensions', 'payments'
        ])->findOrFail($pawnId);

        $this->calculateRedemption();
        $this->calculateExtensionFee();
    }

    public function calculateRedemption()
    {
        $this->redemptionAmount = (float) $this->pawn->getTotalRedemptionAmount();
    }

    // ========================================
    // REDEMPTION (PELUNASAN)
    // ========================================

    public function openRedemptionModal()
    {
        $this->calculateRedemption();
        $this->dispatch('open-redemption-modal');
    }

    public function closeRedemptionModal()
    {
        $this->reset(['redemptionNotes']);
        $this->paymentMethod = 'cash';
        $this->dispatch('close-redemption-modal');
    }

    public function processRedemption()
    {
        if (!in_array($this->pawn->status, ['active', 'extended'])) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Transaksi sudah tidak dalam masa aktif.'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            $interest = (float) $this->pawn->calculateInterest();
            $total = (float) $this->pawn->loan_amount + $interest;

            $this->pawn->update([
                'status' => 'redeemed',
                'redeemed_at' => now(),
                'total_interest' => $interest,
                'total_payment' => $total,
            ]);

            PawnPayment::create([
                'pawn_transaction_id' => $this->pawn->id,
                'user_id' => auth()->id(),
                'amount' => $total,
                'payment_type' => 'full_redemption',
                'payment_method' => $this->paymentMethod,
                'notes' => $this->redemptionNotes,
            ]);

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Pelunasan berhasil! Total: Rp ' . number_format($total, 0, ',', '.')
            ]);

            $this->pawn->refresh();
            $this->dispatch('close-redemption-modal');
            $this->reset(['redemptionNotes']);
            $this->paymentMethod = 'cash';

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal: ' . $e->getMessage()
            ]);
        }
    }

    // ========================================
    // EXTENSION (PERPANJANGAN)
    // ========================================

    public function openExtensionModal()
    {
        $this->calculateExtensionFee();
        $this->dispatch('open-extension-modal');
    }

    public function closeExtensionModal()
    {
        $this->extensionDays = 30;
        $this->paymentMethod = 'cash';
        $this->calculateExtensionFee();
        $this->dispatch('close-extension-modal');
    }

    public function updatedExtensionDays($value)
    {
        $this->extensionDays = (int) $value;
        $this->calculateExtensionFee();
    }

    public function calculateExtensionFee()
    {
        $days = (int) $this->extensionDays;
        $months = ceil($days / 30);
        
        $interestRate = (float) $this->pawn->interest_rate / 100;
        $loanAmount = (float) $this->pawn->loan_amount;

        $this->extensionFee = $loanAmount * $interestRate * $months;
    }

    public function processExtension()
    {
        if (!in_array($this->pawn->status, ['active', 'extended'])) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gadai sudah tidak aktif'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            $newDueDate = $this->pawn->due_date->copy()->addDays((int)$this->extensionDays);

            PawnExtension::create([
                'pawn_transaction_id' => $this->pawn->id,
                'user_id' => auth()->id(),
                'extension_days' => (int)$this->extensionDays,
                'extension_fee' => (float)$this->extensionFee,
                'new_due_date' => $newDueDate,
            ]);

            PawnPayment::create([
                'pawn_transaction_id' => $this->pawn->id,
                'user_id' => auth()->id(),
                'amount' => (float)$this->extensionFee,
                'payment_type' => 'interest',
                'payment_method' => $this->paymentMethod,
                'notes' => "Perpanjangan {$this->extensionDays} hari",
            ]);

            $this->pawn->update([
                'status' => 'extended',
                'due_date' => $newDueDate,
            ]);

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Berhasil diperpanjang hingga " . $newDueDate->format('d M Y')
            ]);

            $this->pawn->refresh();
            $this->extensionDays = 30;
            $this->paymentMethod = 'cash';
            $this->calculateExtensionFee();
            $this->dispatch('close-extension-modal');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.pegadaian.pawn-detail')->layout('layouts.app');
    }
}