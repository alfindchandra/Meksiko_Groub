<?php

namespace App\Livewire\Components;

use App\Models\StockTransfer;
use Livewire\Component;

class PendingTransferBadge extends Component
{
    public $count = 0;

    protected $listeners = ['transferUpdated' => 'loadCount'];

    public function mount()
    {
        $this->loadCount();
    }

    public function loadCount()
    {
        $query = StockTransfer::where('status', 'pending');

        if (auth()->user()->isRider()) {
            $query->where('to_outlet_id', auth()->user()->outlet_id);
        }

        $this->count = $query->count();
    }

    public function render()
    {
        return view('livewire.components.pending-transfer-badge');
    }
}