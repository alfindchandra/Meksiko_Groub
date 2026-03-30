<?php

namespace App\Livewire\Stock;

use App\Models\Stock;
use App\Models\Outlet;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class StockList extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedOutlet = '';
    public $selectedCategory = '';
    public $sortBy = 'product_name';
    public $sortDirection = 'asc';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedOutlet' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = Stock::with(['product.category', 'outlet']);

        // Filter by outlet
        if (auth()->user()->isRider()) {
            $query->where('outlet_id', auth()->user()->outlet_id);
        } elseif ($this->selectedOutlet) {
            $query->where('outlet_id', $this->selectedOutlet);
        }

        // Search
        if ($this->search) {
            $query->whereHas('product', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if ($this->selectedCategory) {
            $query->whereHas('product', function($q) {
                $q->where('category_id', $this->selectedCategory);
            });
        }

        // Sorting
        switch ($this->sortBy) {
            case 'product_name':
                $query->join('products', 'stocks.product_id', '=', 'products.id')
                      ->orderBy('products.name', $this->sortDirection)
                      ->select('stocks.*');
                break;
            case 'quantity':
                $query->orderBy('quantity', $this->sortDirection);
                break;
        }

        $stocks = $query->paginate($this->perPage);
        $outlets = Outlet::active()->get();
        $categories = Category::all();

        return view('livewire.stock.stock-list', [
            'stocks' => $stocks,
            'outlets' => $outlets,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}