<?php

namespace App\Livewire\Outlet;

use App\Models\Stock;
use App\Models\StockTransfer;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

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

        // Recent activity
        $this->recentActivity = StockTransfer::where(function($query) {
                $query->where('from_outlet_id', $this->outlet->id)
                      ->orWhere('to_outlet_id', $this->outlet->id);
            })
            ->with(['fromOutlet', 'toOutlet'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.outlet.dashboard')->layout('layouts.app');
    }
}