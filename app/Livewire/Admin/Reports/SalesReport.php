<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Sale;
use App\Models\Outlet;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SalesReport extends Component
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
        $query = Sale::with(['outlet', 'items.product'])
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);

        if ($this->selectedOutlet) {
            $query->where('outlet_id', $this->selectedOutlet);
        }

        $sales = $query->get();

        // Summary
        $summary = [
            'total_sales' => $sales->count(),
            'total_revenue' => $sales->sum('total'),
            'total_items' => $sales->sum(fn($s) => $s->items->sum('quantity')),
            'avg_transaction' => $sales->avg('total'),
            'total_discount' => $sales->sum('discount'),
        ];

        // Daily trend
        $dailyTrend = Sale::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as transactions'),
                DB::raw('SUM(total) as revenue')
            )
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedOutlet, fn($q) => $q->where('outlet_id', $this->selectedOutlet))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Sales by payment method
        $paymentMethods = Sale::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedOutlet, fn($q) => $q->where('outlet_id', $this->selectedOutlet))
            ->groupBy('payment_method')
            ->get();

        // Top products
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedOutlet, fn($q) => $q->where('sales.outlet_id', $this->selectedOutlet))
            ->select(
                'products.name',
                'products.sku',
                DB::raw('SUM(sale_items.quantity) as total_qty'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Sales by category
        $categoryBreakdown = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedOutlet, fn($q) => $q->where('sales.outlet_id', $this->selectedOutlet))
            ->select(
                'categories.name',
                DB::raw('SUM(sale_items.quantity) as total_qty'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Hourly pattern
        $hourlyPattern = Sale::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as transactions')
            )
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedOutlet, fn($q) => $q->where('outlet_id', $this->selectedOutlet))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Otomatis kirim sinyal update grafik setiap render selesai diproses oleh filter internal (tanggal dsb)
        $this->dispatch('update-sales-charts', data: [
            'dailyTrend' => $dailyTrend,
            'paymentMethods' => $paymentMethods,
            'categoryBreakdown' => $categoryBreakdown,
            'hourlyPattern' => $hourlyPattern,
        ]);

        $outlets = Outlet::where('is_active', true)->get();

        return view('livewire.admin.reports.sales-report', [
            'summary' => $summary,
            'dailyTrend' => $dailyTrend,
            'paymentMethods' => $paymentMethods,
            'topProducts' => $topProducts,
            'categoryBreakdown' => $categoryBreakdown,
            'hourlyPattern' => $hourlyPattern,
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}
