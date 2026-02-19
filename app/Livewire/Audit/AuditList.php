<?php

namespace App\Livewire\Audit;

use App\Models\Audit;
use App\Models\Outlet;
use Livewire\Component;
use Livewire\WithPagination;

class AuditList extends Component
{
    use WithPagination;

    public $search = '';
    public $outletFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $differenceFilter = ''; // all, surplus, deficit, match
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'outletFilter' => ['except' => ''],
        'differenceFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Audit::with(['outlet', 'product', 'auditedBy'])
            ->latest('audited_at');

        // Filter by user access
        if (auth()->user()->isKepalaRuko()) {
            $query->where('outlet_id', auth()->user()->outlet_id);
        } elseif ($this->outletFilter) {
            $query->where('outlet_id', $this->outletFilter);
        }

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('audit_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('product', function($pq) {
                      $pq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('sku', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Difference filter
        if ($this->differenceFilter === 'surplus') {
            $query->whereRaw('physical_quantity > system_quantity');
        } elseif ($this->differenceFilter === 'deficit') {
            $query->whereRaw('physical_quantity < system_quantity');
        } elseif ($this->differenceFilter === 'match') {
            $query->whereRaw('physical_quantity = system_quantity');
        }

        // Date range
        if ($this->dateFrom) {
            $query->whereDate('audited_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('audited_at', '<=', $this->dateTo);
        }

        $audits = $query->paginate($this->perPage);
        $outlets = Outlet::active()->get();

        return view('livewire.audit.audit-list', [
            'audits' => $audits,
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}