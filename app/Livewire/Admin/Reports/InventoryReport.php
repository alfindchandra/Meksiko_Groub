<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Stock;
use App\Models\Outlet;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class InventoryReport extends Component
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
        $endDate = $this->dateTo . ' 23:59:59';

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
            ->whereBetween('created_at', [$this->dateFrom, $endDate])
            ->when($this->selectedOutlet, fn($q) => $q->where('outlet_id', $this->selectedOutlet))
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        $this->dispatch('update-inventory-charts', data: [
            'stockByCategory' => $stockByCategory,
            'stockMovement' => $stockMovement,
        ]);

        $outlets = Outlet::where('is_active', true)->get();

        return view('livewire.admin.reports.inventory-report', [
            'summary' => $summary,
            'stockByCategory' => $stockByCategory,
            'lowStockProducts' => $lowStockProducts,
            'topValueProducts' => $topValueProducts,
            'stockMovement' => $stockMovement,
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}
