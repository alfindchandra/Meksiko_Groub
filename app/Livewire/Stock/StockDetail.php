<?php

namespace App\Livewire\Stock;

use App\Models\Stock;
use App\Models\StockHistory;
use Livewire\Component;

class StockDetail extends Component
{
    public $stock;
    public $stockHistories;
    public $fromDate;
    public $toDate;

    public function mount($id)
    {
        $this->stock = Stock::with(['product.category', 'outlet'])->findOrFail($id);
        $this->fromDate = now()->startOfMonth()->format('Y-m-d');
        $this->toDate = now()->format('Y-m-d');
        $this->loadStockHistories();
    }

    public function updatedFromDate()
    {
        $this->loadStockHistories();
    }

    public function updatedToDate()
    {
        $this->loadStockHistories();
    }

/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Load stock histories for the given product and outlet
     * 
/*******  2627b425-ebac-4243-be1d-c796f63ab160  *******/
    public function loadStockHistories()
    {
        $query = StockHistory::where('product_id', $this->stock->product_id)
            ->where('outlet_id', $this->stock->outlet_id)
            ->with('user')
            ->latest();

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('created_at', [$this->fromDate . ' 00:00:00', $this->toDate . ' 23:59:59']);
        } elseif ($this->fromDate) {
            $query->whereDate('created_at', '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $query->whereDate('created_at', '<=', $this->toDate);
        }

        $this->stockHistories = $query->take(10)->get();
    }

    public function render()
    {
        return view('livewire.stock.stock-detail')->layout('layouts.app');
    }
}