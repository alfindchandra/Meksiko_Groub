<?php

namespace App\Livewire\Transfer;

use App\Models\Outlet;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\TransferItem;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CreateTransfer extends Component
{
    public $fromOutletId;
    public $toOutletId;
    public $notes = '';
    public $items = [];
    public $searchProduct = '';
    public $showProductSearch = false;
    public $availableProducts = [];

    protected $rules = [
        'fromOutletId' => 'required|exists:outlets,id|different:toOutletId',
        'toOutletId' => 'required|exists:outlets,id',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'notes' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'fromOutletId.required' => 'Pilih outlet pengirim',
        'fromOutletId.different' => 'Outlet pengirim dan penerima harus berbeda',
        'toOutletId.required' => 'Pilih outlet penerima',
        'items.*.product_id.required' => 'Pilih produk',
        'items.*.quantity.required' => 'Masukkan jumlah',
        'items.*.quantity.min' => 'Jumlah minimal 1',
    ];

    public function mount()
    {
        // Set default from outlet based on user
        if (auth()->user()->isKepalaRuko()) {
            $this->fromOutletId = auth()->user()->outlet_id;
        }
    }

    public function updatedSearchProduct()
    {
        if (strlen($this->searchProduct) >= 2) {
            $this->availableProducts = Product::where('name', 'like', '%' . $this->searchProduct . '%')
                ->orWhere('sku', 'like', '%' . $this->searchProduct . '%')
                ->limit(10)
                ->get();
            $this->showProductSearch = true;
        } else {
            $this->availableProducts = [];
            $this->showProductSearch = false;
        }
    }

    public function addProduct($productId)
    {
        $product = Product::find($productId);
        
        // Check if product already added
        $exists = collect($this->items)->contains('product_id', $productId);
        
        if (!$exists && $product) {
            // Get available stock from selected outlet
            $stock = Stock::where('product_id', $productId)
                ->where('outlet_id', $this->fromOutletId)
                ->first();

            $this->items[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'quantity' => 1,
                'available_stock' => $stock ? $stock->available : 0,
                'unit' => $product->unit,
            ];
        }

        $this->searchProduct = '';
        $this->showProductSearch = false;
        $this->availableProducts = [];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updateQuantity($index, $quantity)
    {
        if (isset($this->items[$index])) {
            $this->items[$index]['quantity'] = max(1, (int)$quantity);
        }
    }

    public function submit()
    {
        $this->validate();

        if (empty($this->items)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Tambahkan minimal 1 produk untuk transfer'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            // Generate transfer number
            $transferNumber = 'TRF-' . date('Ymd') . '-' . str_pad(
                StockTransfer::whereDate('created_at', today())->count() + 1,
                3,
                '0',
                STR_PAD_LEFT
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
            foreach ($this->items as $item) {
                // Validate stock availability
                $stock = Stock::where('product_id', $item['product_id'])
                    ->where('outlet_id', $this->fromOutletId)
                    ->first();

                if (!$stock || $stock->available < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk: {$item['product_name']}");
                }

                // Create transfer item
                TransferItem::create([
                    'stock_transfer_id' => $transfer->id,
                    'product_id' => $item['product_id'],
                    'quantity_requested' => $item['quantity'],
                ]);

                // Reserve stock
                $stock->reserve($item['quantity']);
            }

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Transfer {$transferNumber} berhasil dibuat!"
            ]);

            return redirect()->route('transfer.detail', $transfer->id);

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
        $outlets = Outlet::active()->get();
        
        // If user is kepala ruko, only show other outlets as destination
        if (auth()->user()->isKepalaRuko()) {
            $outlets = $outlets->where('id', '!=', auth()->user()->outlet_id);
        }

        return view('livewire.transfer.create-transfer', [
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}