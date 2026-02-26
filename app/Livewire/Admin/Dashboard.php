<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\Sale;
use App\Models\PawnTransaction;
use App\Models\Audit;
use App\Models\Outlet;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $dateRange = 'month';
    public $customDateFrom = '';
    public $customDateTo = '';

    protected $listeners = [
        'dateRangeChanged' => 'updatedDateRange',
        'refreshCharts' => '$refresh',
    ];

    public function mount()
    {
        $this->customDateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->customDateTo = now()->format('Y-m-d');
    }

    public function updatedDateRange($value)
    {
        $this->dateRange = $value;
        $this->emitChartUpdate();
    }

    public function updatedCustomDateFrom()
    {
        $this->dateRange = 'custom';
        $this->emitChartUpdate();
    }

    public function updatedCustomDateTo()
    {
        $this->dateRange = 'custom';
        $this->emitChartUpdate();
    }

    private function emitChartUpdate()
    {
        $dateFrom = $this->getDateFrom();
        $dateTo = $this->getDateTo();

        // Mengirim 4 data grafik agar JS bisa update secara dinamis
        $this->dispatch('charts-refreshed', [
            'sales' => $this->getSalesChartData($dateFrom, $dateTo),
            'stock' => $this->getStockByOutletData(),
            'revenueByOutlet' => $this->getRevenueByOutletChartData($dateFrom, $dateTo),
            'salesByCategory' => $this->getSalesByCategoryChartData($dateFrom, $dateTo)
        ]);
    }

    public function render()
    {
        $dateFrom = $this->getDateFrom();
        $dateTo = $this->getDateTo();

        // Overview Stats
        $stats = [
            'total_products' => Product::where('is_active', true)->count(),
            'total_outlets' => Outlet::where('is_active', true)->count(),
            'low_stock_items' => Stock::get()->filter(fn($s) => $s->is_low_stock)->count(),
            'pending_transfers' => StockTransfer::where('status', 'pending')->count(),
            
            // Sales stats
            'period_sales' => Sale::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'period_revenue' => Sale::whereBetween('created_at', [$dateFrom, $dateTo])->sum('total'),
            'period_items_sold' => Sale::whereBetween('created_at', [$dateFrom, $dateTo])
                ->get()
                ->sum(fn($sale) => $sale->items->sum('quantity')),
            
            // Pawn stats
            'active_pawns' => PawnTransaction::where('status', 'active')->count(),
            'active_pawns_amount' => PawnTransaction::where('status', 'active')->sum('loan_amount'),
            'overdue_pawns' => PawnTransaction::where('status', 'active')
                ->where('due_date', '<', now())
                ->count(),
        ];

        // Sales Chart Data (Last 7 Days)
        $salesChart = $this->getSalesChartData($dateFrom, $dateTo);

        // Revenue by Outlet
        $revenueByOutlet = Sale::select('outlet_id', DB::raw('SUM(total) as total_revenue'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('outlet')
            ->groupBy('outlet_id')
            ->orderByDesc('total_revenue')
            ->get();

        // Top Products
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$dateFrom, $dateTo])
            ->select('products.name', 'products.sku', DB::raw('SUM(sale_items.quantity) as total_qty'), DB::raw('SUM(sale_items.subtotal) as total_revenue'))
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Stock by Outlet Chart
        $stockByOutlet = $this->getStockByOutletData();

        // Recent Activities
        $recentActivities = $this->getRecentActivities();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'salesChart' => $salesChart,
            'revenueByOutlet' => $revenueByOutlet,
            'revenueByOutletChart' => $this->getRevenueByOutletChartData($dateFrom, $dateTo),
            'salesByCategoryChart' => $this->getSalesByCategoryChartData($dateFrom, $dateTo),
            'topProducts' => $topProducts,
            'stockByOutlet' => $stockByOutlet,
            'recentActivities' => $recentActivities,
        ])->layout('layouts.app');
    }

    private function getDateFrom()
    {
        return match($this->dateRange) {
            'today' => today(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            'custom' => $this->customDateFrom,
            default => now()->startOfMonth(),
        };
    }

    private function getDateTo()
    {
        return match($this->dateRange) {
            'today' => now(),
            'week' => now()->endOfWeek(),
            'month' => now()->endOfMonth(),
            'year' => now()->endOfYear(),
            'custom' => $this->customDateTo,
            default => now(),
        };
    }

    private function getSalesChartData($from, $to)
    {
        $days = [];
        $revenues = [];
        $transactions = [];

        $period = \Carbon\CarbonPeriod::create($from, $to);
        
        foreach ($period as $date) {
            $days[] = $date->format('d M');
            
            $dayRevenue = Sale::whereDate('created_at', $date)->sum('total');
            $revenues[] = (float)$dayRevenue;
            
            $dayTransactions = Sale::whereDate('created_at', $date)->count();
            $transactions[] = $dayTransactions;
        }

        return [
            'labels' => $days,
            'revenue' => $revenues,
            'transactions' => $transactions,
        ];
    }

    private function getStockByOutletData()
    {
        $stocks = Stock::with('outlet')
            ->select('outlet_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('outlet_id')
            ->get();

        return [
            'labels' => $stocks->map(fn($s) => $s->outlet ? $s->outlet->name : 'Unknown')->toArray(),
            'data' => $stocks->pluck('total_qty')->toArray(),
            'colors' => ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6', '#f43f5e'],
        ];
    }

    private function getRevenueByOutletChartData($from, $to)
    {
        $revenues = Sale::select('outlet_id', DB::raw('SUM(total) as total_revenue'))
            ->whereBetween('created_at', [$from, $to])
            ->with('outlet')
            ->groupBy('outlet_id')
            ->orderByDesc('total_revenue')
            ->get();

        return [
            'labels' => $revenues->map(fn($r) => $r->outlet ? $r->outlet->name : 'Unknown')->toArray(),
            'data' => $revenues->pluck('total_revenue')->toArray(),
        ];
    }

    private function getSalesByCategoryChartData($from, $to)
    {
        $categories = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$from, $to])
            ->select('categories.name', DB::raw('SUM(sale_items.quantity) as total_qty'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_qty')
            ->get();

        return [
            'labels' => $categories->pluck('name')->toArray(),
            'data' => $categories->pluck('total_qty')->toArray(),
        ];
    }

    private function getRecentActivities()
    {
        $activities = collect();

        // Recent sales
        $sales = Sale::with(['outlet', 'user'])
            ->latest()
            ->take(3)
            ->get()
            ->map(fn($s) => [
                'type' => 'sale',
                'icon' => '💰',
                'title' => "Penjualan #{$s->sale_number}",
                'description' => "{$s->outlet->name} • {$s->user->name}",
                'amount' => $s->total,
                'time' => $s->created_at,
            ]);

        // Recent transfers
        $transfers = StockTransfer::with(['fromOutlet', 'toOutlet'])
            ->latest()
            ->take(3)
            ->get()
            ->map(fn($t) => [
                'type' => 'transfer',
                'icon' => '📦',
                'title' => "Transfer {$t->transfer_number}",
                'description' => "{$t->fromOutlet->name} → {$t->toOutlet->name}",
                'amount' => null,
                'time' => $t->created_at,
            ]);

        // Recent pawns
        $pawns = PawnTransaction::with(['outlet', 'user'])
            ->latest()
            ->take(2)
            ->get()
            ->map(fn($p) => [
                'type' => 'pawn',
                'icon' => '💎',
                'title' => "Gadai {$p->pawn_number}",
                'description' => "{$p->customer_name} • {$p->outlet->name}",
                'amount' => $p->loan_amount,
                'time' => $p->created_at,
            ]);

        return $activities->merge($sales)
            ->merge($transfers)
            ->merge($pawns)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }
}