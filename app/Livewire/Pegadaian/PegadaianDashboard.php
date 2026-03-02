<?php

namespace App\Livewire\Pegadaian;

use App\Models\PawnTransaction;
use App\Models\PawnPayment;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PegadaianDashboard extends Component
{
    public $dateFilter = 'month';
    public $customDateFrom = '';
    public $customDateTo = '';

    public function mount()
    {
        $this->customDateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->customDateTo = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = PawnTransaction::query();

        // Filter by outlet (non-admin)
        if (!auth()->user()->isAdminPusat() && auth()->user()->outlet_id) {
            $query->where('outlet_id', auth()->user()->outlet_id);
        }

        // Date filtering
        switch ($this->dateFilter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'custom':
                if ($this->customDateFrom && $this->customDateTo) {
                    $query->whereBetween('created_at', [$this->customDateFrom, $this->customDateTo]);
                }
                break;
        }

        // Statistics
        $stats = [
            // Active loans
            'total_active' => PawnTransaction::where('status', 'active')
                ->when(!auth()->user()->isAdminPusat(), fn($q) => $q->where('outlet_id', auth()->user()->outlet_id))
                ->count(),
            
            'total_active_amount' => PawnTransaction::where('status', 'active')
                ->when(!auth()->user()->isAdminPusat(), fn($q) => $q->where('outlet_id', auth()->user()->outlet_id))
                ->sum('loan_amount'),

            // Overdue
            'total_overdue' => PawnTransaction::where('status', 'active')
                ->where('due_date', '<', now())
                ->when(!auth()->user()->isAdminPusat(), fn($q) => $q->where('outlet_id', auth()->user()->outlet_id))
                ->count(),

            'total_overdue_amount' => PawnTransaction::where('status', 'active')
                ->where('due_date', '<', now())
                ->when(!auth()->user()->isAdminPusat(), fn($q) => $q->where('outlet_id', auth()->user()->outlet_id))
                ->sum('loan_amount'),

            // Period stats
            'period_new' => $query->count(),
            'period_loan_amount' => $query->sum('loan_amount'),
            'period_redeemed' => (clone $query)->where('status', 'redeemed')->count(),
            'period_extended' => (clone $query)->where('status', 'extended')->count(),

            // Revenue
            'period_admin_fee' => $query->sum('admin_fee'),
            'period_interest' => PawnPayment::where('payment_type', 'interest')
                ->when(!auth()->user()->isAdminPusat(), function($q) {
                    $q->whereHas('pawnTransaction', fn($qq) => $qq->where('outlet_id', auth()->user()->outlet_id));
                })
                ->whereBetween('created_at', $this->getDateRange())
                ->sum('amount'),
        ];

        // Recent transactions
        $recentPawns = PawnTransaction::with(['user'])
            ->when(!auth()->user()->isAdminPusat(), fn($q) => $q->where('outlet_id', auth()->user()->outlet_id))
            ->latest()
            ->take(5)
            ->get();

        // Due soon (7 days)
        $dueSoon = PawnTransaction::where('status', 'active')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->when(!auth()->user()->isAdminPusat(), fn($q) => $q->where('outlet_id', auth()->user()->outlet_id))
            ->orderBy('due_date')
            ->take(10)
            ->get();

        // Category breakdown
        $categoryStats = PawnTransaction::select('item_category', DB::raw('count(*) as total'), DB::raw('sum(loan_amount) as amount'))
            ->where('status', 'active')
            ->when(!auth()->user()->isAdminPusat(), fn($q) => $q->where('outlet_id', auth()->user()->outlet_id))
            ->groupBy('item_category')
            ->get();

        return view('livewire.pegadaian.pegadaian-dashboard', [
            'stats' => $stats,
            'recentPawns' => $recentPawns,
            'dueSoon' => $dueSoon,
            'categoryStats' => $categoryStats,
        ])->layout('layouts.app');
    }

    private function getDateRange()
    {
        switch ($this->dateFilter) {
            case 'today':
                return [today(), now()];
            case 'week':
                return [now()->startOfWeek(), now()->endOfWeek()];
            case 'month':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'custom':
                return [$this->customDateFrom, $this->customDateTo];
            default:
                return [today(), now()];
        }
    }
}