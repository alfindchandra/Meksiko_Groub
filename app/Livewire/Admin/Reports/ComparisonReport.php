<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Sale;
use App\Models\PawnTransaction;
use App\Models\Outlet;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ComparisonReport extends Component
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
        // Sales comparison by outlet
        $outletComparison = Sale::select(
                'outlet_id',
                DB::raw('COUNT(*) as transactions'),
                DB::raw('SUM(total) as revenue')
            )
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->with('outlet')
            ->groupBy('outlet_id')
            ->get();

        // Monthly comparison (last 6 months)
        $monthlyComparison = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyComparison[] = [
                'month' => $date->format('M Y'),
                'sales' => Sale::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total'),
                'pawns' => PawnTransaction::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('loan_amount'),
            ];
        }

        // Category performance comparison
        $categoryPerformance = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$this->dateFrom, $this->dateTo])
            ->select(
                'categories.name',
                DB::raw('SUM(sale_items.quantity) as total_qty'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT sales.id) as transaction_count')
            )
            ->groupBy('categories.id', 'categories.name')
            ->get();

        $this->dispatch('update-comparison-charts', data: [
            'monthlyComparison' => $monthlyComparison,
        ]);

        $outlets = Outlet::where('is_active', true)->get();

        return view('livewire.admin.reports.comparison-report', [
            'outletComparison' => $outletComparison,
            'monthlyComparison' => $monthlyComparison,
            'categoryPerformance' => $categoryPerformance,
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}
