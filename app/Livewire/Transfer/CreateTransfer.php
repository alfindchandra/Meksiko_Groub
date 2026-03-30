<?php

namespace App\Livewire\Transfer;

use App\Models\StockTransfer;
use App\Models\TransferItem;
use App\Models\Product;
use App\Models\Outlet;
use App\Models\Stock;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CreateTransfer extends Component
{
    public $fromOutletId = '';
    public $toOutletId = '';
    public $notes = '';
    
    public $searchProduct = '';
    public $showProductSearch = false;
    public $availableProducts = [];
    public $selectedProducts = [];

    protected $rules = [
        'fromOutletId' => 'required|exists:outlets,id',
        'toOutletId' => 'required|exists:outlets,id|different:fromOutletId',
        'notes' => 'nullable|string|max:500',
        'selectedProducts.*.quantity' => 'required|integer|min:1',
    ];

    protected $messages = [
        'fromOutletId.required' => 'Pilih outlet asal',
        'toOutletId.required' => 'Pilih outlet tujuan',
        'toOutletId.different' => 'Outlet tujuan harus berbeda dengan outlet asal',
        'selectedProducts.*.quantity.required' => 'Jumlah wajib diisi',
        'selectedProducts.*.quantity.min' => 'Jumlah minimal 1',
    ];

    public function mount()
    {
        // RIDER: Hanya bisa request dari warehouse
        if (auth()->user()->isRider()) {
            $warehouse = Outlet::where('type', 'warehouse')->first();
            $this->fromOutletId = $warehouse?->id ?? '';
            $this->toOutletId = auth()->user()->outlet_id;
        }
    }

    public function updatedSearchProduct()
    {
        if (!$this->fromOutletId) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Pilih outlet asal terlebih dahulu'
            ]);
            return;
        }

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
        if (!$this->fromOutletId) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Pilih outlet asal terlebih dahulu'
            ]);
            return;
        }

        // Check if already added
        foreach ($this->selectedProducts as $p) {
            if ((int)$p['product_id'] === $productId) {
                $this->dispatch('notify', [
                    'type' => 'warning',
                    'message' => 'Produk sudah ada dalam daftar'
                ]);
                $this->searchProduct = '';
                $this->showProductSearch = false;
                return;
            }
        }

        $product = Product::find($productId);
        if (!$product) return;

        // Get available stock from source outlet
        $stock = Stock::where('product_id', $productId)
            ->where('outlet_id', $this->fromOutletId)
            ->first();

        $availableStock = $stock ? $stock->available : 0;

        $this->selectedProducts[] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'unit' => $product->unit,
            'available_stock' => $availableStock,
            'quantity' => 1,
        ];

        $this->searchProduct = '';
        $this->showProductSearch = false;
        $this->availableProducts = [];
    }

    public function removeProduct($index)
    {
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts);
    }

    public function updatedFromOutletId()
    {
        // Reset selected products when changing source
        $this->selectedProducts = [];
    }

    public function submit()
    {
        $this->validate();

        if (empty($this->selectedProducts)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Tambahkan minimal 1 produk untuk ditransfer'
            ]);
            return;
        }

        // Validate stock availability
        foreach ($this->selectedProducts as $index => $item) {
            if ($item['quantity'] > $item['available_stock']) {
                $this->addError("selectedProducts.{$index}.quantity",
                    "Stok tidak cukup. Tersedia: {$item['available_stock']}");
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => "Stok {$item['product_name']} tidak mencukupi"
                ]);
                return;
            }
        }

        try {
            DB::beginTransaction();

            // Generate transfer number
            $transferNumber = 'TRF-' . date('Ymd') . '-' . str_pad(
                StockTransfer::whereDate('created_at', today())->count() + 1,
                3, '0', STR_PAD_LEFT
            );

            // Create transfer
            $transfer = StockTransfer::create([
                'transfer_number' => $transferNumber,
                'from_outlet_id' => $this->fromOutletId,
                'to_outlet_id' => $this->toOutletId,
                'requested_by' => auth()->id(),
                'status' => 'pending',
                'notes' => $this->notes,
            ]);

            // Create transfer items and reserve stock
            foreach ($this->selectedProducts as $item) {
                TransferItem::create([
                    'stock_transfer_id' => $transfer->id,
                    'product_id' => $item['product_id'],
                    'quantity_requested' => $item['quantity'],
                ]);

                // Reserve stock
                $stock = Stock::where('product_id', $item['product_id'])
                    ->where('outlet_id', $this->fromOutletId)
                    ->first();

                if ($stock) {
                    $stock->reserve($item['quantity']);
                }
            }

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Transfer {$transferNumber} berhasil dibuat dan menunggu persetujuan!"
            ]);

            return redirect()->route('transfer.create');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal membuat transfer: ' . $e->getMessage()
            ]);
        }
    }

   public function render()
{
    $allOutlets = Outlet::where('is_active', true)->get();
    $fromOutlets = collect();
    $toOutlets = collect();

    if (auth()->user()->isAdminPusat()) {
        // Admin bisa pilih semua, tapi difilter agar tidak bisa memilih outlet yang sama
        $fromOutlets = $allOutlets->where('id', '!=', $this->toOutletId);
        $toOutlets = $allOutlets->where('id', '!=', $this->fromOutletId);
        
    } elseif (auth()->user()->isRider()) {
        // Rider: Dari Gudang ke Outlet milik sendiri
        $fromOutlets = $allOutlets->where('type', 'warehouse')
                                  ->where('id', '!=', $this->toOutletId);
                                  
        $toOutlets = $allOutlets->where('id', auth()->user()->outlet_id)
                                ->where('id', '!=', $this->fromOutletId);
    }

    return view('livewire.transfer.create-transfer', [
        'fromOutlets' => $fromOutlets,
        'toOutlets' => $toOutlets,
    ])->layout('layouts.app');
}
}