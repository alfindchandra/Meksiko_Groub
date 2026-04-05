<?php

namespace App\Livewire\Audit;

use App\Models\Audit;
use Livewire\Component;
use Livewire\WithFileUploads;

class AuditPayment extends Component
{
    use WithFileUploads;

    public $payAudits = [];
    public $totalPayment = 0;
    public $totalCredit = 0;
    public $proof;
    public $isAdmin = false;
    public $outletFilter = null;

    public function mount()
    {
        // Determine user access level
        $this->isAdmin = auth()->user()->isAdminPusat();

        // If not admin, must be rider/outlet
        if (!$this->isAdmin && !auth()->user()->isRider()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Set outlet filter for riders
        if (auth()->user()->isRider()) {
            $this->outletFilter = auth()->user()->outlet_id;
        }

        $this->loadAudits();
    }

    public function loadAudits()
    {
        $query = Audit::with(['product', 'outlet'])
            ->where('status', 'pending');

        // Filter by outlet if rider
        if (!$this->isAdmin && $this->outletFilter) {
            $query->where('outlet_id', $this->outletFilter);
        }

        $this->payAudits = $query->latest('audited_at')->get();

        // Calculate totals: minus (must pay) + plus (credit)
        $this->totalPayment = $this->payAudits
            ->where('payment_amount', '>', 0)
            ->sum('payment_amount');
        
        $this->totalCredit = $this->payAudits
            ->where('payment_amount', '<', 0)
            ->sum(fn($a) => abs($a['payment_amount']));
    }

    public function submitPayment()
    {
        $this->validate([
            'proof' => $this->totalPayment > 0 ? 'required|image|max:5120' : 'nullable|image|max:5120', // 5MB max
        ]);

        $path = $this->proof ? $this->proof->store('audit-payments', 'public') : null;

        foreach ($this->payAudits as $audit) {
            $audit->update([
                'status' => 'payment_submitted',
                'proof_of_payment' => $path
            ]);
        }

        // Notify admins about payment submission
        try {
            $admins = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'admin_pusat'))->get();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'audit_payment_submitted',
                    'title' => 'Pembayaran Audit Disubmit',
                    'message' => 'Outlet ' . (auth()->user()->outlet?->name ?? 'Unknown') . ' submit pembayaran audit Rp ' . number_format($this->totalPayment, 0, ',', '.'),
                    'reference_type' => 'Audit',
                    'reference_id' => $this->payAudits->first()?->id ?? 0,
                    'is_read' => false,
                ]);
            }
        } catch (\Exception $e) {
            \Log::warning('Payment notification error: ' . $e->getMessage());
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Pembayaran berhasil disubmit dan menunggu konfirmasi Admin.'
        ]);

        return redirect()->route('audit.list');
    }

    public function render()
    {
        return view('livewire.audit.audit-payment')->layout('layouts.app');
    }
}