<?php

namespace App\Livewire\Shipment;

use App\Models\Shipment;
use App\Models\Outlet;
use Livewire\Component;
use Livewire\WithPagination;

class ShipmentList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $outletFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 15;

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

    public function render()
    {
        $query = Shipment::with(['stockTransfer', 'toOutlet', 'fromOutlet'])
            ->latest();

        // Filter by user access
        if (auth()->user()->isKepalaRuko()) {
            $query->where(function($q) {
                $q->where('from_outlet_id', auth()->user()->outlet_id)
                  ->orWhere('to_outlet_id', auth()->user()->outlet_id);
            });
        }

        // Search
        if ($this->search) {
            $query->where('shipment_number', 'like', '%' . $this->search . '%');
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

        // Date range
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $shipments = $query->paginate($this->perPage);
        $outlets = Outlet::active()->get();

        return view('livewire.shipment.shipment-list', [
            'shipments' => $shipments,
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}