<?php

namespace App\Livewire\Admin\Reports;

use App\Models\PawnTransaction;
use App\Models\Outlet;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PawnReport extends Component
{
    public $dateFrom;
    public $dateTo;
    public $selectedOutlet = '';

    protected $listeners = [
        'refreshCharts' => '$refresh',
    ];

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatedDateFrom()
    {
        $this->dispatch('charts-refreshed');
    }

    public function updatedDateTo()
    {
        $this->dispatch('charts-refreshed');
    }

    public function updatedSelectedOutlet()
    {
        $this->dispatch('charts-refreshed');
    }

    public function render()
    {
        $query = PawnTransaction::with(['outlet'])
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);

        if ($this->selectedOutlet) {
            $query->where('outlet_id', $this->selectedOutlet);
        }

        $pawns = $query->get();

        // Summary
        $summary = [
            'total_transactions' => $pawns->count(),
            'total_loan_amount' => $pawns->sum('loan_amount'),
            'total_admin_fee' => $pawns->sum('admin_fee'),
            'active_pawns' => $pawns->where('status', 'active')->count(),
            'redeemed_pawns' => $pawns->where('status', 'redeemed')->count(),
            'overdue_pawns' => $pawns->where('status', 'active')->filter(fn($p) => $p->due_date < now())->count(),
        ];

        // Daily trend
        $dailyTrend = PawnTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as transactions'),
                DB::raw('SUM(loan_amount) as total_loan')
            )
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedOutlet, fn($q) => $q->where('outlet_id', $this->selectedOutlet))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // By category
        $categoryBreakdown = PawnTransaction::select(
                'item_category',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(loan_amount) as total_loan')
            )
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedOutlet, fn($q) => $q->where('outlet_id', $this->selectedOutlet))
            ->groupBy('item_category')
            ->get();

        // Status distribution
        $statusDistribution = PawnTransaction::select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedOutlet, fn($q) => $q->where('outlet_id', $this->selectedOutlet))
            ->groupBy('status')
            ->get();

        $this->dispatch('update-pawn-charts', data: [
            'dailyTrend' => $dailyTrend,
            'statusDistribution' => $statusDistribution
        ]);

        $outlets = Outlet::where('is_active', true)->get();

        return view('livewire.admin.reports.pawn-report', [
            'summary' => $summary,
            'dailyTrend' => $dailyTrend,
            'categoryBreakdown' => $categoryBreakdown,
            'statusDistribution' => $statusDistribution,
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}
