<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class SalesList extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $paymentMethodFilter = '';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        // Default last 7 days
        if (!$this->dateFrom) {
            $this->dateFrom = now()->subDays(7)->format('Y-m-d');
        }
        if (!$this->dateTo) {
            $this->dateTo = now()->format('Y-m-d');
        }
    }

    public function render()
    {
        $query = Sale::with(['outlet', 'user', 'items'])
            ->latest('sale_date');

        // Filter by outlet (for non-admin)
        if (auth()->user()->isKepalaRuko() || !auth()->user()->isAdminPusat()) {
            $query->where('outlet_id', auth()->user()->outlet_id);
        }

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('sale_number', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
            });
        }

        // Date range
        if ($this->dateFrom) {
            $query->whereDate('sale_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('sale_date', '<=', $this->dateTo);
        }

        // Payment method
        if ($this->paymentMethodFilter) {
            $query->where('payment_method', $this->paymentMethodFilter);
        }

        $sales = $query->paginate($this->perPage);

        // Statistics
        $stats = [
            'total_sales' => $query->count(),
            'total_revenue' => $query->sum('total'),
            'total_items' => $query->get()->sum(fn($sale) => $sale->items->sum('quantity')),
        ];

        return view('livewire.sales.sales-list', [
            'sales' => $sales,
            'stats' => $stats,
        ])->layout('layouts.app');
    }
}