<?php

namespace App\Livewire\Outlet;

use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\Sale;
use App\Models\Audit;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Dashboard extends Component
{
    public $outlet;
    public $totalStockItems;
    public $totalStockValue;
    public $lowStockCount;
    public $pendingTransfers;
    public $lowStockProducts;
    public $recentActivity;

    public function mount()
    {
        $this->outlet = auth()->user()->outlet;
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        // Total stock items
        $this->totalStockItems = Stock::where('outlet_id', $this->outlet->id)
            ->sum('quantity');

        // Total stock value
        $this->totalStockValue = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->where('stocks.outlet_id', $this->outlet->id)
            ->sum(DB::raw('stocks.quantity * products.price'));

        // Low stock count
        $this->lowStockCount = Stock::where('outlet_id', $this->outlet->id)
            ->with('product')
            ->get()
            ->filter(fn($stock) => $stock->is_low_stock)
            ->count();

        // Pending transfers
        $this->pendingTransfers = StockTransfer::where('to_outlet_id', $this->outlet->id)
            ->where('status', 'pending')
            ->count();

        // Low stock products (top 5)
        $this->lowStockProducts = Stock::where('outlet_id', $this->outlet->id)
            ->with('product')
            ->get()
            ->filter(fn($stock) => $stock->is_low_stock)
            ->take(5);

        // Combine recent activity from multiple sources
        $this->recentActivity = $this->getMergedRecentActivity();
    }

    private function getMergedRecentActivity(): Collection
    {
        $transfers = StockTransfer::where(function($query) {
                $query->where('from_outlet_id', $this->outlet->id)
                      ->orWhere('to_outlet_id', $this->outlet->id);
            })
            ->with(['fromOutlet', 'toOutlet'])
            ->get()
            ->map(function($transfer) {
                return (object) [
                    'id' => $transfer->id,
                    'type' => 'transfer',
                    'title' => $transfer->transfer_number,
                    'description' => 'dari ' . $transfer->fromOutlet->name,
                    'status' => $transfer->status,
                    'created_at' => $transfer->created_at,
                    'model' => $transfer,
                ];
            });

        $sales = Sale::where('outlet_id', $this->outlet->id)
            ->with('user')
            ->get()
            ->map(function($sale) {
                return (object) [
                    'id' => $sale->id,
                    'type' => 'sale',
                    'title' => $sale->sale_number,
                    'description' => 'Transaksi: ' . $sale->customer_name,
                    'status' => 'completed',
                    'created_at' => $sale->sale_date ?? $sale->created_at,
                    'model' => $sale,
                    'total' => $sale->total,
                ];
            });

        $audits = Audit::where('outlet_id', $this->outlet->id)
            ->with('product', 'auditedBy')
            ->get()
            ->map(function($audit) {
                return (object) [
                    'id' => $audit->id,
                    'type' => 'audit',
                    'title' => $audit->audit_number,
                    'description' => 'Audit: ' . $audit->product->name,
                    'status' => 'completed',
                    'created_at' => $audit->audited_at,
                    'model' => $audit,
                ];
            });

        // Merge and sort by created_at, get latest 10
        return collect($transfers)
            ->merge($sales)
            ->merge($audits)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();
    }

    public function render()
    {
        return view('livewire.outlet.dashboard')->layout('layouts.app');
    }
}