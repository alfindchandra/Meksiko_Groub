<?php

namespace App\Livewire\Audit;

use App\Models\Audit;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Outlet;
use App\Services\StockService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateAudit extends Component
{
    public $outletId;
    public $selectedProducts = [];
    public $searchProduct = '';
    public $showProductSearch = false;
    public $availableProducts = [];
    public $notes = '';
    public $auditDate;

    protected function rules()
    {
        return [
            'outletId' => 'required|exists:outlets,id',
            'selectedProducts.*.physical_quantity' => 'required|integer|min:0',
            'selectedProducts.*.reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'auditDate' => 'required|date|before_or_equal:today',
        ];
    }

    protected $messages = [
        'outletId.required' => 'Pilih outlet yang akan diaudit',
        'selectedProducts.*.physical_quantity.required' => 'Masukkan jumlah fisik',
        'selectedProducts.*.physical_quantity.min' => 'Jumlah tidak boleh negatif',
        'auditDate.required' => 'Tanggal audit wajib diisi',
        'auditDate.before_or_equal' => 'Tanggal audit tidak boleh di masa depan',
    ];

    public function mount()
    {
        if (auth()->user()->isRider()) {
            $this->outletId = auth()->user()->outlet_id;
        }
        $this->auditDate = today()->format('Y-m-d');
    }

    public function updatedSearchProduct()
    {
        if (strlen($this->searchProduct) >= 2) {
            $this->availableProducts = Product::where(function ($query) {
        $query->where('name', 'like', '%' . $this->searchProduct . '%')
              ->orWhere('sku', 'like', '%' . $this->searchProduct . '%');
    })
    ->orderBy('name', 'asc') 
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
        if (!$this->outletId) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Pilih outlet terlebih dahulu'
            ]);
            return;
        }

        $product = Product::find($productId);
        
        $exists = collect($this->selectedProducts)->contains('product_id', $productId);
        
        if (!$exists && $product) {
            $stock = Stock::where('product_id', $productId)
                ->where('outlet_id', $this->outletId)
                ->first();

            $systemQuantity = $stock ? $stock->quantity : 0;

            $this->selectedProducts[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'system_quantity' => $systemQuantity,
                'physical_quantity' => $systemQuantity,
                'difference' => 0,
                'reason' => '',
                'unit' => $product->unit,
            ];
        }

        $this->searchProduct = '';
        $this->showProductSearch = false;
        $this->availableProducts = [];
    }

    public function removeProduct($index)
    {
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts);
    }

    public function updatedSelectedProducts($value, $key)
    {
        if (str_contains($key, 'physical_quantity')) {
            $index = explode('.', $key)[0];
            if (isset($this->selectedProducts[$index])) {
                $physical = (int) $this->selectedProducts[$index]['physical_quantity'];
                $system = (int) $this->selectedProducts[$index]['system_quantity'];
                $this->selectedProducts[$index]['difference'] = $physical - $system;
            }
        }
    }

 public function loadAllProducts()
{
    if (!$this->outletId) {
        $this->addError('outletId', 'Pilih outlet terlebih dahulu');
        return;
    }

    $allProducts = Product::orderBy('name', 'asc')->get();

    foreach ($allProducts as $product) {
        $exists = collect($this->selectedProducts)->contains('product_id', $product->id);

        if (!$exists) {
            $stock = Stock::where('product_id', $product->id)
                ->where('outlet_id', $this->outletId)
                ->first();

            $systemQuantity = $stock ? $stock->quantity : 0;

            $this->selectedProducts[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'system_quantity' => $systemQuantity,
                'physical_quantity' => $systemQuantity,
                'difference' => 0,
                'reason' => '',
                'unit' => $product->unit,
            ];
        }
    }

   
    $this->selectedProducts = collect($this->selectedProducts)
        ->sortBy('product_name')
        ->values()
        ->all();
}

    public function submit()
    {
        // Validate basic rules first
        $this->validate([
            'outletId' => 'required|exists:outlets,id',
            'auditDate' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ]);

        if (empty($this->selectedProducts)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Tambahkan minimal 1 produk untuk diaudit'
            ]);
            return;
        }

        // Validate products with differences
        $hasError = false;
        foreach ($this->selectedProducts as $index => $item) {
            if ($item['difference'] != 0 && empty($item['reason'])) {
                $this->addError("selectedProducts.{$index}.reason", 'Alasan wajib diisi untuk produk dengan perbedaan');
                $hasError = true;
            }
        }

        if ($hasError) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Lengkapi alasan untuk produk dengan perbedaan stok'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            $stockService = app(StockService::class);
            $auditCount = 0;

            foreach ($this->selectedProducts as $item) {
                // Only create audit if there's a difference
                if ($item['difference'] != 0) {
                    // Generate audit number
                    $auditNumber = 'AUD-' . date('Ymd') . '-' . str_pad(
                        Audit::whereDate('created_at', today())->count() + 1,
                        3,
                        '0',
                        STR_PAD_LEFT
                    );

                    // Create audit record
                    $product = Product::find($item['product_id']);
                    $paymentAmount = ($item['system_quantity'] - $item['physical_quantity']) * ($product ? $product->price : 0);

                    Audit::create([
                        'audit_number' => $auditNumber,
                        'outlet_id' => $this->outletId,
                        'product_id' => $item['product_id'],
                        'audited_by' => auth()->id(),
                        'system_quantity' => $item['system_quantity'],
                        'physical_quantity' => $item['physical_quantity'],
                        'reason' => $item['reason'] ?? 'Audit adjustment',
                        'notes' => $this->notes,
                        'audited_at' => \Carbon\Carbon::parse($this->auditDate)->setTimeFrom(now()),
                        'status' => 'pending',
                        'payment_amount' => $paymentAmount,
                    ]);

                    // Stock is NO LONGER adjusted immediately. It requires payment/confirmation.
                    
                    $auditCount++;
                }
            }

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Audit berhasil! {$auditCount} produk dengan perbedaan telah disesuaikan."
            ]);

            return redirect()->route('audit.list');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Audit Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal menyimpan audit: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $outlets = Outlet::active()->get();
        
        if (auth()->user()->isRider()) {
            $outlets = $outlets->where('id', auth()->user()->outlet_id);
        }

        return view('livewire.audit.create-audit', [
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}