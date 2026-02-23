<?php

namespace App\Livewire\Pegadaian;

use App\Models\PawnTransaction;
use App\Models\PawnPayment;
use App\Models\PawnExtension;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PawnDetail extends Component
{
    public PawnTransaction $pawn;
    
    // Redemption modal
    public $showRedemptionModal = false;
    public $redemptionAmount = 0;
    public $paymentMethod = 'cash';
    public $redemptionNotes = '';

    // Extension modal
    public $showExtensionModal = false;
    public $extensionDays = 30; // Default 30 hari
    public $extensionFee = 0;

    public function mount($pawnId)
    {
        $this->pawn = PawnTransaction::with([
            'outlet', 'user', 'extensions', 'payments'
        ])->findOrFail($pawnId);

        $this->calculateRedemption();
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
        $this->showRedemptionModal = true;
    }

    public function closeRedemptionModal()
    {
        $this->showRedemptionModal = false;
        $this->reset(['redemptionNotes', 'paymentMethod']);
    }

    public function processRedemption()
    {
        // Pastikan hanya status yang bisa dilunasi
        if (!in_array($this->pawn->status, ['active', 'extended'])) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Transaksi sudah tidak dalam masa aktif/lelang.'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            $interest = (float) $this->pawn->calculateInterest();
            $total = (float) $this->pawn->loan_amount + $interest;

            // Update pawn status
            $this->pawn->update([
                'status' => 'redeemed',
                'redeemed_at' => now(),
                'total_interest' => $interest,
                'total_payment' => $total,
            ]);

            // Record payment
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
                'message' => 'Pelunasan berhasil diproses!'
            ]);

            $this->closeRedemptionModal();
            $this->pawn->refresh();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    // ========================================
    // EXTENSION (PERPANJANGAN)
    // ========================================

    public function openExtensionModal()
    {
        $this->calculateExtensionFee();
        $this->showExtensionModal = true;
    }

    public function closeExtensionModal()
    {
        $this->showExtensionModal = false;
        $this->reset(['extensionDays', 'extensionFee', 'paymentMethod']);
        $this->extensionDays = 30;
    }

    // Hook otomatis saat nilai extensionDays berubah di select/input
    public function updatedExtensionDays($value)
    {
        $this->extensionDays = (int) $value; // Force casting ke integer
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
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Gadai sudah tidak aktif']);
            return;
        }

        try {
            DB::beginTransaction();

            // FIX: Gunakan copy() agar objek asli tidak termutasi di memori sebelum save
            $newDueDate = $this->pawn->due_date->copy()->addDays((int)$this->extensionDays);

            // Simpan riwayat perpanjangan
            PawnExtension::create([
                'pawn_transaction_id' => $this->pawn->id,
                'user_id' => auth()->id(),
                'extension_days' => (int)$this->extensionDays,
                'extension_fee' => (float)$this->extensionFee,
                'new_due_date' => $newDueDate,
            ]);

            // Catat pembayaran bunga perpanjangan
            PawnPayment::create([
                'pawn_transaction_id' => $this->pawn->id,
                'user_id' => auth()->id(),
                'amount' => (float)$this->extensionFee,
                'payment_type' => 'interest',
                'payment_method' => $this->paymentMethod,
                'notes' => "Perpanjangan tenor {$this->extensionDays} hari.",
            ]);

            // Update data transaksi utama
            $this->pawn->update([
                'status' => 'extended',
                'due_date' => $newDueDate,
            ]);

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Tenor berhasil diperpanjang hingga " . $newDueDate->format('d M Y')
            ]);

            $this->closeExtensionModal();
            $this->pawn->refresh();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.pegadaian.pawn-detail')->layout('layouts.app');
    }
}