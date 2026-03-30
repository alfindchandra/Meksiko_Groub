<?php

namespace App\Livewire\Auditor;

use App\Models\Audit;
use App\Models\StockTransfer;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\StockHistory;
use Livewire\Component;

class AuditorDashboard extends Component
{
    public $dateFrom;
    public $dateTo;

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

   public function render()
{
    // Filter rentang tanggal
    $dateRange = [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'];

    // Ambil Riwayat Stok (Audit & Mutasi)
    $stockHistories = StockHistory::with(['product', 'outlet', 'user'])
        ->whereBetween('created_at', $dateRange)
        ->latest()
        ->take(10)
        ->get();

    // Ambil Data Penjualan Terbaru untuk Auditor
    $recentSales = Sale::with(['outlet', 'user', 'items'])
        ->whereBetween('sale_date', $dateRange)
        ->latest()
        ->take(10)
        ->get();

    // Statistics
    $stats = [
        'total_audits' => Audit::whereBetween('created_at', $dateRange)->count(),
        'total_transfers' => StockTransfer::whereBetween('created_at', $dateRange)->count(),
        'total_sales_count' => Sale::whereBetween('sale_date', $dateRange)->count(),
        'total_revenue' => Sale::whereBetween('sale_date', $dateRange)->sum('total'),
        'low_stock_items' => Stock::where('quantity', '<=', 10)->count(), // Contoh threshold
        'stock_variance' => Audit::whereBetween('created_at', $dateRange)
            ->selectRaw('SUM(ABS(physical_quantity - system_quantity)) as variance')
            ->value('variance') ?? 0,
    ];

    // Aktivitas Mencurigakan (Contoh: Penyesuaian stok > 50 atau manual adjustment)
    $suspiciousActivities = StockHistory::whereIn('type', ['adjustment', 'correction'])
        ->whereBetween('created_at', $dateRange)
        ->where(function($q) {
            $q->where('quantity_change', '>', 50)
              ->orWhere('quantity_change', '<', -50);
        })
        ->with(['product', 'outlet', 'user'])
        ->latest()
        ->get();

    return view('livewire.auditor.auditor-dashboard', [
        'stats' => $stats,
        'stockHistories' => $stockHistories,
        'recentSales' => $recentSales,
        'suspiciousActivities' => $suspiciousActivities,
    ])->layout('layouts.app');
}
}