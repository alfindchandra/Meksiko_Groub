<?php

namespace App\Livewire\Stock;

use App\Models\Stock;
use App\Models\Outlet;
use Livewire\Component;
use Livewire\WithPagination;

class LowStockAlert extends Component
{
    use WithPagination;

    public $outletFilter = '';
    public $perPage = 15;

    public function render()
    {
        $query = Stock::with(['product', 'outlet'])
            ->get()
            ->filter(fn($stock) => $stock->is_low_stock);

        // Filter by user access
        if (auth()->user()->isRider()) {
            $query = $query->where('outlet_id', auth()->user()->outlet_id);
        } elseif ($this->outletFilter) {
            $query = $query->where('outlet_id', $this->outletFilter);
        }

        // Convert to collection and paginate manually
        $lowStocks = $query->values();
        
        $outlets = Outlet::active()->get();

        return view('livewire.stock.low-stock-alert', [
            'lowStocks' => $lowStocks,
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}