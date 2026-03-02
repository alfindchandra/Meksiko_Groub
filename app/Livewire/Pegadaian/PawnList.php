<?php

namespace App\Livewire\Pegadaian;

use App\Models\PawnTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class PawnList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $categoryFilter = '';
    public $dateFrom = '';
    public $dateTo = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = PawnTransaction::with(['outlet', 'user'])->latest();

        // Filter by outlet (non-admin)
        if (!auth()->user()->isAdminPusat() && auth()->user()->outlet_id) {
            $query->where('outlet_id', auth()->user()->outlet_id);
        }

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('pawn_number', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_id_number', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                  ->orWhere('item_name', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Category filter
        if ($this->categoryFilter) {
            $query->where('item_category', $this->categoryFilter);
        }

        // Date range
        if ($this->dateFrom) {
            $query->whereDate('start_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('start_date', '<=', $this->dateTo);
        }

        $pawns = $query->paginate(15);

        // Stats
        $statsQuery = PawnTransaction::query();
        if (!auth()->user()->isAdminPusat() && auth()->user()->outlet_id) {
            $statsQuery->where('outlet_id', auth()->user()->outlet_id);
        }

        $stats = [
            'total_active' => (clone $statsQuery)->where('status', 'active')->count(),
            'total_overdue' => (clone $statsQuery)->where('status', 'active')
                ->where('due_date', '<', now())
                ->count(),
            'total_loan' => (clone $statsQuery)->where('status', 'active')->sum('loan_amount'),
        ];

        return view('livewire.pegadaian.pawn-list', [
            'pawns' => $pawns,
            'stats' => $stats,
        ])->layout('layouts.app');
    }
}