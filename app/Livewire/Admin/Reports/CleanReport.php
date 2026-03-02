<?php

namespace App\Livewire\Admin\Reports;

use App\Models\McTransaction;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CleanReport extends Component
{
    public $dateFrom;
    public $dateTo;

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

    public function render()
    {
        $endDate = $this->dateTo . ' 23:59:59';

        $query = McTransaction::with(['partner', 'items.service'])
            ->whereBetween('created_at', [$this->dateFrom, $endDate]);

        $transactions = $query->get();

        // Summary
        $summary = [
            'total_transactions' => $transactions->count(),
            'total_revenue' => $transactions->sum('total_amount'),
            'paid_transactions' => $transactions->where('payment_status', 'paid')->count(),
            'completed_transactions' => $transactions->where('status', 'completed')->count(),
        ];

        // Daily trend
        $dailyTrend = McTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as transactions'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereBetween('created_at', [$this->dateFrom, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Service Breakdown (Top Services)
        $serviceBreakdown = DB::table('mc_transaction_items')
            ->join('mc_services', 'mc_transaction_items.service_id', '=', 'mc_services.id')
            ->join('mc_transactions', 'mc_transaction_items.transaction_id', '=', 'mc_transactions.id')
            ->whereBetween('mc_transactions.created_at', [$this->dateFrom, $endDate])
            ->select(
                'mc_services.name',
                DB::raw('COUNT(mc_transaction_items.id) as count'),
                DB::raw('SUM(mc_transaction_items.subtotal) as total_revenue')
            )
            ->groupBy('mc_services.id', 'mc_services.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Order Types Breakdown
        $orderTypes = McTransaction::select(
                'order_type',
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$this->dateFrom, $endDate])
            ->groupBy('order_type')
            ->get();

        $this->dispatch('update-clean-charts', data: [
            'dailyTrend' => $dailyTrend,
            'serviceBreakdown' => $serviceBreakdown,
            'orderTypes' => $orderTypes
        ]);

        return view('livewire.admin.reports.clean-report', [
            'summary' => $summary,
            'dailyTrend' => $dailyTrend,
            'serviceBreakdown' => $serviceBreakdown,
            'orderTypes' => $orderTypes
        ])->layout('layouts.app');
    }
}
