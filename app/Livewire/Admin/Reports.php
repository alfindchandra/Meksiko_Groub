<?php

namespace App\Livewire\Admin;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Outlet;
use App\Models\Category;
use App\Models\PawnTransaction;
use App\Models\StockTransfer;
use App\Models\Stock;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Reports extends Component
{
    public $reportType = 'sales';
    public $dateFrom;
    public $dateTo;
    public $selectedOutlet = '';
    public $selectedCategory = '';

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function render()
    {
        $data = match($this->reportType) {
            'sales' => $this->getSalesReport(),
            'inventory' => $this->getInventoryReport(),
            'pawn' => $this->getPawnReport(),
            'comparison' => $this->getComparisonReport(),
            default => $this->getSalesReport(),
        };

        $outlets = Outlet::where('is_active', true)->get();
        $categories = Category::all();

        return view('livewire.admin.reports', array_merge($data, [
            'outlets' => $outlets,
            'categories' => $categories,
        ]))->layout('layouts.app');
    }

    private function getSalesReport()
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

        return [
            'summary' => $summary,
            'dailyTrend' => $dailyTrend,
            'paymentMethods' => $paymentMethods,
            'topProducts' => $topProducts,
            'categoryBreakdown' => $categoryBreakdown,
            'hourlyPattern' => $hourlyPattern,
        ];
    }

    private function getInventoryReport()
    {
        $query = Stock::with(['product.category', 'outlet']);

        if ($this->selectedOutlet) {
            $query->where('outlet_id', $this->selectedOutlet);
        }

        $stocks = $query->get();

        // Summary
        $summary = [
            'total_products' => $stocks->unique('product_id')->count(),
            'total_quantity' => $stocks->sum('quantity'),
            'total_reserved' => $stocks->sum('reserved'),
            'low_stock_items' => $stocks->filter(fn($s) => $s->is_low_stock)->count(),
            'total_value' => $stocks->sum(fn($s) => $s->quantity * $s->product->price),
        ];

        // Stock by category
        $stockByCategory = $stocks->groupBy('product.category.name')
            ->map(fn($items) => [
                'quantity' => $items->sum('quantity'),
                'value' => $items->sum(fn($s) => $s->quantity * $s->product->price),
            ]);

        // Low stock products
        $lowStockProducts = $stocks->filter(fn($s) => $s->is_low_stock)
            ->sortBy('quantity')
            ->take(10);

        // Top value products
        $topValueProducts = $stocks->sortByDesc(fn($s) => $s->quantity * $s->product->price)
            ->take(10);

        // Stock movement trend
        $stockMovement = DB::table('stock_histories')
            ->select(
                DB::raw('DATE(created_at) as date'),
                'type',
                DB::raw('SUM(quantity_change) as total_qty')
            )
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedOutlet, fn($q) => $q->where('outlet_id', $this->selectedOutlet))
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        return [
            'summary' => $summary,
            'stockByCategory' => $stockByCategory,
            'lowStockProducts' => $lowStockProducts,
            'topValueProducts' => $topValueProducts,
            'stockMovement' => $stockMovement,
        ];
    }

    private function getPawnReport()
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

        return [
            'summary' => $summary,
            'dailyTrend' => $dailyTrend,
            'categoryBreakdown' => $categoryBreakdown,
            'statusDistribution' => $statusDistribution,
        ];
    }

    private function getComparisonReport()
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

        return [
            'outletComparison' => $outletComparison,
            'monthlyComparison' => $monthlyComparison,
            'categoryPerformance' => $categoryPerformance,
        ];
    }

    public function exportPDF()
    {
        // Implementation for PDF export
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => 'Export PDF sedang dalam proses...'
        ]);
    }

    public function exportExcel()
    {
        // Implementation for Excel export
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => 'Export Excel sedang dalam proses...'
        ]);
    }
}