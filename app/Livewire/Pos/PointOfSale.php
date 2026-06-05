<?php

namespace App\Livewire\Pos;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockHistory;
use App\Models\ProductDiscountTier;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PointOfSale extends Component
{
    public $searchProduct = '';
    public $cart = [];
    public $customerName = '';
    public $customerPhone = '';
    public $paymentMethod = 'cash';
    public $paidAmount = 0;
    public $globalDiscount = 0;
    public $tax = 0;
    public $notes = '';
    public $selectedVariantProductId = null;
    public $selectedVariantId = null;

    public function mount()
    {
        if (!auth()->user()->outlet_id) {
            abort(403, 'POS hanya untuk user yang memiliki outlet');
        }
    }

    public function addToCart($productId, $variantId = null)
    {
        $product = Product::with('activeVariants', 'activeDiscountTiers')->find($productId);
        if (!$product) return;

        // If product has variants and no variant selected, show variant selector
        if ($product->activeVariants->count() > 0 && !$variantId) {
            $this->selectedVariantProductId = $productId;
            $this->dispatch('show-variant-modal');
            return;
        }

        $stock = Stock::where('product_id', $productId)
            ->where('outlet_id', auth()->user()->outlet_id)
            ->first();

        if (!$stock || $stock->quantity < 1) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Stok tidak tersedia'
            ]);
            return;
        }

        // Get variant info if selected
        $variant = null;
        $unitPrice = (float)$product->price;
        $productUnit = $product->unit;
        
        if ($variantId) {
            $variant = \App\Models\ProductVariant::find($variantId);
            if ($variant) {
                $unitPrice = (float)$variant->price;
                $productUnit = $variant->unit_name;
            }
        }

        $cartKey = $variantId ? "product_{$productId}_variant_{$variantId}" : "product_{$productId}";

        $found = false;
        foreach ($this->cart as $index => $item) {
            if ($item['cart_key'] === $cartKey) {
                if ((int)$this->cart[$index]['quantity'] >= (int)$stock->quantity) {
                    $this->dispatch('notify', [
                        'type' => 'error',
                        'message' => 'Stok tidak mencukupi'
                    ]);
                    return;
                }
                $this->cart[$index]['quantity'] = (int)$this->cart[$index]['quantity'] + 1;
                $this->recalculateCartItem($index);
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->cart[] = [
                'cart_key' => $cartKey,
                'product_id' => (int)$product->id,
                'variant_id' => $variantId,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'product_unit' => $productUnit,
                'unit_price' => $unitPrice,
                'quantity' => 1,
                'available_stock' => (int)$stock->quantity,
                'item_discount_percentage' => 0,
                'item_discount_amount' => 0,
                'subtotal' => $unitPrice,
                'has_tier_discount' => $product->activeDiscountTiers->count() > 0,
            ];
        }

        $this->searchProduct = '';
        $this->selectedVariantProductId = null;
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function updateQuantity($index, $quantity)
    {
        $quantity = (int)$quantity;
        
        if ($quantity < 1) {
            $this->removeFromCart($index);
            return;
        }

        if ($quantity > (int)$this->cart[$index]['available_stock']) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Stok tidak mencukupi'
            ]);
            return;
        }

        $this->cart[$index]['quantity'] = $quantity;
        $this->recalculateCartItem($index);
    }

    private function recalculateCartItem($index)
    {
        $item = $this->cart[$index];
        $quantity = (int)$item['quantity'];
        $unitPrice = (float)$item['unit_price'];

        // Get applicable discount
        $discountPercentage = ProductDiscountTier::getDiscountForQuantity(
            (int)$item['product_id'],
            $quantity
        );

        $subtotalBeforeDiscount = $quantity * $unitPrice;
        $discountAmount = $subtotalBeforeDiscount * ($discountPercentage / 100);
        $subtotalAfterDiscount = $subtotalBeforeDiscount - $discountAmount;

        $this->cart[$index]['item_discount_percentage'] = $discountPercentage;
        $this->cart[$index]['item_discount_amount'] = $discountAmount;
        $this->cart[$index]['subtotal'] = $subtotalAfterDiscount;
    }

    public function getSubtotalProperty()
    {
        return (float)collect($this->cart)->sum('subtotal');
    }

    public function getTotalItemDiscountProperty()
    {
        return (float)collect($this->cart)->sum('item_discount_amount');
    }

    public function getTotalProperty()
    {
        return (float)$this->subtotal - (float)$this->globalDiscount + (float)$this->tax;
    }

    public function getChangeProperty()
    {
        if ($this->paymentMethod !== 'cash') {
            return 0;
        }
        return max(0, (float)$this->paidAmount - (float)$this->total);
    }

    public function selectVariant($variantId)
    {
        if ($this->selectedVariantProductId) {
            $this->addToCart($this->selectedVariantProductId, $variantId);
            $this->selectedVariantProductId = null;
        }
    }

    public function getVariantsForProduct($productId)
    {
        $product = Product::find($productId);
        if (!$product) return collect();
        return $product->activeVariants;
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Keranjang kosong']);
            return;
        }

        if ($this->paymentMethod === 'cash' && (float)$this->paidAmount < (float)$this->total) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Jumlah bayar kurang']);
            return;
        }

        try {
            DB::beginTransaction();

            $saleNumber = 'SALE-' . date('Ymd') . '-' . str_pad(
                Sale::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            $totalDiscount = (float)$this->totalItemDiscount + (float)$this->globalDiscount;

            $sale = Sale::create([
                'sale_number' => $saleNumber,
                'outlet_id' => auth()->user()->outlet_id,
                'user_id' => auth()->id(),
                'customer_name' => $this->customerName,
                'customer_phone' => $this->customerPhone,
                'payment_method' => $this->paymentMethod,
                'subtotal' => (float)$this->subtotal + (float)$this->totalItemDiscount, // Before all discounts
                'discount' => $totalDiscount,
                'tax' => (float)$this->tax,
                'total' => (float)$this->total,
                'paid_amount' => $this->paymentMethod === 'cash' ? (float)$this->paidAmount : (float)$this->total,
                'change_amount' => (float)$this->change,
                'notes' => $this->notes,
                'sale_date' => now(),
            ]);

            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => (int)$item['product_id'],
                    'quantity' => (int)$item['quantity'],
                    'unit_price' => (float)$item['unit_price'],
                    'discount' => (float)$item['item_discount_amount'],
                    'subtotal' => (float)$item['subtotal'],
                ]);

                $stock = Stock::where('product_id', $item['product_id'])
                    ->where('outlet_id', auth()->user()->outlet_id)
                    ->first();

                if ($stock) {
                    $qtyBefore = $stock->quantity;
                    $stock->decrement('quantity', (int)$item['quantity']);

                    StockHistory::create([
                        'product_id' => (int)$item['product_id'],
                        'outlet_id' => auth()->user()->outlet_id,
                        'type' => 'out',
                        'quantity_before' => $qtyBefore,
                        'quantity_change' => (int)$item['quantity'],
                        'quantity_after' => $stock->fresh()->quantity,
                        'reference_type' => 'Sale',
                        'reference_id' => $sale->id,
                        'user_id' => auth()->id(),
                        'notes' => "Penjualan {$saleNumber}",
                    ]);
                }
            }

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Transaksi berhasil! ' . $saleNumber
            ]);

            return redirect()->route('pos.receipt', $sale->id);

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
        $products = [];
        if (strlen($this->searchProduct) >= 2) {
            $products = Product::with('activeDiscountTiers')
                ->where('is_active', true)
                ->where(function($q) {
                    $q->where('name', 'like', '%' . $this->searchProduct . '%')
                      ->orWhere('sku', 'like', '%' . $this->searchProduct . '%');
                })
                ->limit(20)
                ->get();
        }

        return view('livewire.pos.point-of-sale', [
            'products' => $products,
        ])->layout('layouts.app');
    }
}