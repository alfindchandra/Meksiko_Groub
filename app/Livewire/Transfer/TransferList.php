<?php

namespace App\Livewire\Transfer;

use App\Models\StockTransfer;
use App\Models\Outlet;
use Livewire\Component;
use Livewire\WithPagination;

class TransferList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $outletFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingOutletFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = StockTransfer::with(['fromOutlet', 'toOutlet', 'requestedBy'])
            ->latest();

        // Filter by user access
        if (auth()->user()->isRider()) {
            $query->where(function($q) {
                $q->where('from_outlet_id', auth()->user()->outlet_id)
                  ->orWhere('to_outlet_id', auth()->user()->outlet_id);
            });
        }

        // Search
        if ($this->search) {
            $query->where('transfer_number', 'like', '%' . $this->search . '%');
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Outlet filter
        if ($this->outletFilter) {
            $query->where(function($q) {
                $q->where('from_outlet_id', $this->outletFilter)
                  ->orWhere('to_outlet_id', $this->outletFilter);
            });
        }

        // Date range filter
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $transfers = $query->paginate($this->perPage);
        $outlets = Outlet::active()->get();

        return view('livewire.transfer.transfer-list', [
            'transfers' => $transfers,
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}