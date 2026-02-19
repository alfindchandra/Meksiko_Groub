<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $showModal = false;
    public $editMode = false;
    
    public $productId;
    public $sku;
    public $name;
    public $description;
    public $category_id;
    public $unit = 'pcs';
    public $price;
    public $min_stock = 10;
    public $is_active = true;

    protected $rules = [
        'sku' => 'required|string|max:50|unique:products,sku',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'category_id' => 'required|exists:categories,id',
        'unit' => 'required|string|max:20',
        'price' => 'required|numeric|min:0',
        'min_stock' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'sku.required' => 'SKU wajib diisi',
        'sku.unique' => 'SKU sudah digunakan',
        'name.required' => 'Nama produk wajib diisi',
        'category_id.required' => 'Kategori wajib dipilih',
        'price.required' => 'Harga wajib diisi',
        'price.min' => 'Harga tidak boleh negatif',
        'min_stock.required' => 'Minimum stok wajib diisi',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'productId', 'sku', 'name', 'description', 
            'category_id', 'unit', 'price', 'min_stock', 'is_active', 'editMode'
        ]);
        $this->resetValidation();
    }

    public function edit($productId)
    {
        $product = Product::findOrFail($productId);
        
        $this->productId = $product->id;
        $this->sku = $product->sku;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->category_id = $product->category_id;
        $this->unit = $product->unit;
        $this->price = $product->price;
        $this->min_stock = $product->min_stock;
        $this->is_active = $product->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editMode) {
            $this->validate([
                'sku' => 'required|string|max:50|unique:products,sku,' . $this->productId,
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'category_id' => 'required|exists:categories,id',
                'unit' => 'required|string|max:20',
                'price' => 'required|numeric|min:0',
                'min_stock' => 'required|integer|min:0',
                'is_active' => 'boolean',
            ]);

            $product = Product::findOrFail($this->productId);
            $product->update([
                'sku' => $this->sku,
                'name' => $this->name,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'unit' => $this->unit,
                'price' => $this->price,
                'min_stock' => $this->min_stock,
                'is_active' => $this->is_active,
            ]);

            $message = 'Produk berhasil diupdate!';
        } else {
            $this->validate();

            Product::create([
                'sku' => $this->sku,
                'name' => $this->name,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'unit' => $this->unit,
                'price' => $this->price,
                'min_stock' => $this->min_stock,
                'is_active' => $this->is_active,
            ]);

            $message = 'Produk berhasil ditambahkan!';
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);

        $this->closeModal();
    }

    public function toggleActive($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update(['is_active' => !$product->is_active]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Status produk berhasil diubah!'
        ]);
    }

    public function delete($productId)
    {
        try {
            Product::findOrFail($productId)->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Produk berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal menghapus produk. Mungkin masih digunakan di transaksi lain.'
            ]);
        }
    }

    public function render()
    {
        $query = Product::with('category')->latest();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        $products = $query->paginate(15);
        $categories = Category::all();

        return view('livewire.admin.data.product-management', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}