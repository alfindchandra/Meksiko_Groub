<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\Sale;
use App\Models\PawnTransaction;
use App\Models\Audit;
use App\Models\Outlet;
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
        // Mengirim data terbaru agar JS bisa langsung update
        $this->dispatch('charts-refreshed', [
            'sales' => $this->getSalesChartData($this->getDateFrom(), $this->getDateTo()),
            'stock' => $this->getStockMovementData($this->getDateFrom(), $this->getDateTo())
        ]);
    }

    public function updatedCustomDateFrom()
    {
        $this->dateRange = 'custom';
        $this->dispatch('charts-refreshed', [
            'sales' => $this->getSalesChartData($this->getDateFrom(), $this->getDateTo()),
            'stock' => $this->getStockMovementData($this->getDateFrom(), $this->getDateTo())
        ]);
    }

    public function updatedCustomDateTo()
    {
        $this->dateRange = 'custom';
        $this->dispatch('charts-refreshed', [
            'sales' => $this->getSalesChartData($this->getDateFrom(), $this->getDateTo()),
            'stock' => $this->getStockMovementData($this->getDateFrom(), $this->getDateTo())
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

        // Stock Movement Chart
        $stockMovement = $this->getStockMovementData($dateFrom, $dateTo);

        // Recent Activities
        $recentActivities = $this->getRecentActivities();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'salesChart' => $salesChart,
            'revenueByOutlet' => $revenueByOutlet,
            'topProducts' => $topProducts,
            'stockMovement' => $stockMovement,
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

    private function getStockMovementData($from, $to)
    {
        $movements = DB::table('stock_histories')
            ->select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(quantity_change) as total_qty'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('type')
            ->get();

        return [
            'labels' => $movements->pluck('type')->map(fn($t) => ucfirst(str_replace('_', ' ', $t)))->toArray(),
            'data' => $movements->pluck('total_qty')->toArray(),
            'colors' => ['#10b981', '#ef4444', '#f59e0b', '#3b82f6', '#8b5cf6'],
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