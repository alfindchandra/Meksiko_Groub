<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Outlet;
use App\Models\Stock;
use App\Services\StockService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class GoodsReceipt extends Component
{
    public $warehouseId;
    public $searchProduct = '';
    public $showProductSearch = false;
    public $availableProducts = [];
    public $receivedProducts = [];
    public $supplierName = '';
    public $invoiceNumber = '';
    public $receiptDate;
    public $notes = '';

    public function mount()
    {
        $warehouse = Outlet::where('type', 'warehouse')->first();
        $this->warehouseId = $warehouse?->id ?? '';
        $this->receiptDate = today()->format('Y-m-d');
    }

    public function updatedSearchProduct()
    {
        if (strlen($this->searchProduct) >= 2) {
            $this->availableProducts = Product::where('is_active', true)
                ->where(function($q) {
                    $q->where('name', 'like', '%' . $this->searchProduct . '%')
                      ->orWhere('sku', 'like', '%' . $this->searchProduct . '%');
                })
                ->limit(10)
                ->get()
                ->toArray();

            $this->showProductSearch = true;
        } else {
            $this->availableProducts = [];
            $this->showProductSearch = false;
        }
    }

    public function addProduct($productId)
    {
        foreach ($this->receivedProducts as $p) {
            if ((int)$p['product_id'] === (int)$productId) {
                $this->dispatch('notify', ['type' => 'warning', 'message' => 'Produk sudah ada']);
                $this->searchProduct = '';
                $this->showProductSearch = false;
                return;
            }
        }

        $product = Product::find($productId);
        if (!$product) return;

        $this->receivedProducts[] = [
            'product_id' => (int)$product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'unit' => $product->unit,
            'quantity' => 1,
            'unit_cost' => (float)$product->price,
            'total_cost' => (float)$product->price,
        ];

        $this->searchProduct = '';
        $this->showProductSearch = false;
        $this->availableProducts = [];
    }

    public function removeProduct($index)
    {
        unset($this->receivedProducts[$index]);
        $this->receivedProducts = array_values($this->receivedProducts);
    }

    public function updatedReceivedProducts($value, $key)
    {
        if (str_ends_with($key, '.quantity') || str_ends_with($key, '.unit_cost')) {
            $index = (int) explode('.', $key)[0];
            if (isset($this->receivedProducts[$index])) {
                $qtyInput = $this->receivedProducts[$index]['quantity'] ?? 0;
                $costInput = $this->receivedProducts[$index]['unit_cost'] ?? 0;
                
                $qty = $qtyInput === '' ? 0 : (float)$qtyInput;
                $cost = $costInput === '' ? 0 : (float)$costInput;
                
                $this->receivedProducts[$index]['total_cost'] = $qty * $cost;
            }
        }
    }

    public function submit()
    {
        if (!$this->warehouseId) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Pilih gudang']);
            return;
        }

        if (empty($this->receivedProducts)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Tambahkan minimal 1 produk']);
            return;
        }

        try {
            DB::beginTransaction();

            $stockService = app(StockService::class);

            foreach ($this->receivedProducts as $item) {
                $stockService->adjustStock(
                    (int)$item['product_id'],
                    (int)$this->warehouseId,
                    (int)$item['quantity'],
                    'in',
                    auth()->id(),
                    "Penerimaan dari {$this->supplierName} - Invoice: {$this->invoiceNumber}"
                );
            }

            DB::commit();

            $totalQty = (int)collect($this->receivedProducts)->sum(fn($item) => (float)($item['quantity'] === '' ? 0 : $item['quantity']));
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Berhasil! {$totalQty} unit masuk ke gudang."
            ]);

            $this->reset(['receivedProducts', 'supplierName', 'invoiceNumber', 'notes']);
            $this->receiptDate = today()->format('Y-m-d');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $warehouses = Outlet::where('type', 'warehouse')
            ->where('is_active', true)
            ->get();

        return view('livewire.admin.goods-receipt', [
            'warehouses' => $warehouses,
        ])->layout('layouts.app');
    }
}