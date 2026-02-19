<?php

namespace App\Livewire\Components;

use App\Models\Stock;
use Livewire\Component;

class LowStockBadge extends Component
{
    public $count = 0;

    protected $listeners = ['stockUpdated' => 'loadCount'];

    public function mount()
    {
        $this->loadCount();
    }

    public function loadCount()
    {
        $query = Stock::with('product')->get();

        if (auth()->user()->isKepalaRuko()) {
            $query = $query->where('outlet_id', auth()->user()->outlet_id);
        }

        $this->count = $query->filter(fn($stock) => $stock->is_low_stock)->count();
    }

    public function render()
    {
        return view('livewire.components.low-stock-badge');
    }
}