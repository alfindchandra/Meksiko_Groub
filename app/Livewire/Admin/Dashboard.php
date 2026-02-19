<?php

namespace App\Livewire\Admin;

use App\Models\Outlet;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransfer;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalOutlets;
    public $totalProducts;
    public $totalStockValue;
    public $pendingTransfers;
    public $lowStockItems;
    public $recentTransfers;
    public $stockByOutlet;

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        // Total outlets
        $this->totalOutlets = Outlet::active()->count();

        // Total products
        $this->totalProducts = Product::active()->count();

        // Total stock value (approximate)
        $this->totalStockValue = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->sum(DB::raw('stocks.quantity * products.price'));

        // Pending transfers
        $this->pendingTransfers = StockTransfer::where('status', 'pending')->count();

        // Low stock items
        $this->lowStockItems = Stock::with('product')
            ->get()
            ->filter(fn($stock) => $stock->is_low_stock)
            ->count();

        // Recent transfers
        $this->recentTransfers = StockTransfer::with(['fromOutlet', 'toOutlet'])
            ->latest()
            ->take(5)
            ->get();

        // Stock by outlet
        $this->stockByOutlet = Outlet::withCount(['stocks as total_items' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->active()
            ->get();
    }

    public function refreshData()
    {
        $this->loadStatistics();
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Data berhasil diperbarui!'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.app');
    }
}