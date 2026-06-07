<?php

namespace App\Livewire\Pos;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Outlet;
use Livewire\Component;

class AuditorPos extends Component
{
    // Outlet
    public $searchOutlet      = '';
    public $selectedOutletId  = null;
    public $selectedOutletName = '';

    // Produk & Cart
    public $searchProduct = '';
    public $cart = [];

    // Setoran
    public $nominalTitipan = 0;

    // Summary
    public $summaryText = '';

    // Variant modal
    public $selectedVariantProductId = null;

    // ─── Outlet ──────────────────────────────────────────────────────────────

    public function selectOutlet($id)
    {
        $outlet = Outlet::find($id);
        if ($outlet) {
            $this->selectedOutletId   = $outlet->id;
            $this->selectedOutletName = $outlet->name;
            $this->searchOutlet       = $outlet->name . ' (' . $outlet->city . ')';
            $this->generateSummary();
        }
    }

    public function resetSelectedOutlet()
    {
        $this->selectedOutletId   = null;
        $this->selectedOutletName = '';
        $this->searchOutlet       = '';
        $this->generateSummary();
    }

    // ─── Cart ─────────────────────────────────────────────────────────────────

    /**
     * Dipanggil dari hasil pencarian.
     * Jika produk punya varian → tampilkan modal pilih varian.
     */
    public function addToCart($productId, $variantId = null)
    {
        $product = Product::with('activeVariants')->find($productId);
        if (!$product) return;

        // Produk punya varian, belum dipilih → tampilkan modal
        if ($product->activeVariants->count() > 0 && $variantId === null) {
            $this->selectedVariantProductId = $productId;
            return;
        }

        $this->doAddToCart($productId, $variantId);
    }

    /**
     * Dipanggil dari modal saat user memilih HARGA DASAR.
     * Bypass cek varian agar tidak loop membuka modal lagi.
     */
    public function addToCartBase($productId)
    {
        $this->selectedVariantProductId = null;
        $this->doAddToCart($productId, null, true);
    }

    private function doAddToCart($productId, $variantId = null, bool $forceBase = false)
    {
        $product = Product::with('activeVariants')->find($productId);
        if (!$product) return;

        // Tentukan harga & satuan
        $unitPrice   = (float) $product->price;
        $productUnit = $product->unit ?? 'pcs';
        $variantLabel = null;

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant) {
                $unitPrice    = (float) $variant->price;
                $productUnit  = $variant->unit_name;
                $variantLabel = $variant->unit_name;
            }
        }

        $cartKey = $variantId ? "product_{$productId}_variant_{$variantId}" : "product_{$productId}";

        // Cek duplikat → tambah qty
        foreach ($this->cart as $index => $item) {
            if ($item['cart_key'] === $cartKey) {
                $this->cart[$index]['quantity']++;
                $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
                $this->searchProduct = '';
                $this->selectedVariantProductId = null;
                $this->generateSummary();
                return;
            }
        }

        // Tambah baru
        $this->cart[] = [
            'cart_key'     => $cartKey,
            'product_id'   => (int) $product->id,
            'variant_id'   => $variantId,
            'variant_label'=> $variantLabel,
            'product_name' => $product->name,
            'product_sku'  => $product->sku,
            'unit'         => $productUnit,
            'unit_price'   => $unitPrice,
            'quantity'     => 1,
            'subtotal'     => $unitPrice,
        ];

        $this->searchProduct = '';
        $this->selectedVariantProductId = null;
        $this->generateSummary();
    }

    public function selectVariant($variantId)
    {
        if ($this->selectedVariantProductId) {
            $productId = $this->selectedVariantProductId;
            $this->selectedVariantProductId = null;
            $this->doAddToCart($productId, $variantId);
        }
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->generateSummary();
    }

    public function updateQuantity($index, $qty)
    {
        $qty = (int) $qty;
        if ($qty < 1) {
            $this->removeFromCart($index);
            return;
        }
        $this->cart[$index]['quantity'] = $qty;
        $this->cart[$index]['subtotal'] = $qty * $this->cart[$index]['unit_price'];
        $this->generateSummary();
    }

    public function updatedNominalTitipan()
    {
        $this->generateSummary();
    }

    // ─── Computed ─────────────────────────────────────────────────────────────

    public function getSubtotalProperty(): float
    {
        return (float) collect($this->cart)->sum('subtotal');
    }

    public function getKekuranganProperty(): float
    {
        $titipan = (float) ($this->nominalTitipan ?: 0);
        return max(0, $this->subtotal - $titipan);
    }

    public function getLebihProperty(): float
    {
        $titipan = (float) ($this->nominalTitipan ?: 0);
        return max(0, $titipan - $this->subtotal);
    }

    // ─── Summary Text ─────────────────────────────────────────────────────────

    public function generateSummary()
    {
        if (empty($this->cart)) {
            $this->summaryText = '';
            return;
        }

        $outletLabel = $this->selectedOutletName ?: 'Ruko';
        $lines = [];
        $lines[] = "📋 LAPORAN BARANG LAKU SAAT SO";
        $lines[] = "Cabang: {$outletLabel}";
        $lines[] = "Tanggal: " . now()->translatedFormat('d F Y');
        $lines[] = str_repeat("-", 35);

        foreach ($this->cart as $i => $item) {
            $num   = $i + 1;
            $unit  = $item['unit'];
            // Tampilkan label varian di summary jika ada
            $label = $item['variant_label'] ?? null;
            $displayUnit = $label ? $label : $unit;

            $lines[] = "{$num}. {$item['product_name']}";
            $lines[] = "      {$item['quantity']} {$displayUnit} x Rp " . number_format($item['unit_price'], 0, ',', '.');
            $lines[] = "       = Rp " . number_format($item['subtotal'], 0, ',', '.');
        }

        $lines[] = str_repeat("-", 35);
        $lines[] = "TOTAL  : Rp " . number_format($this->subtotal, 0, ',', '.');

        $titipan = (float) ($this->nominalTitipan ?: 0);
        if ($titipan > 0) {
            $lines[] = "TITIPAN: Rp " . number_format($titipan, 0, ',', '.');
            if ($this->kekurangan > 0) {
                $lines[] = "KURANG : Rp " . number_format($this->kekurangan, 0, ',', '.');
            } elseif ($this->lebih > 0) {
                $lines[] = "LEBIH  : Rp " . number_format($this->lebih, 0, ',', '.');
            } else {
                $lines[] = "DONE";
            }
        }
        $lines[] = "@MAS APAN MEKSIKO";
        $lines[] = "@dita Kemsiko Admin";
        $lines[] = "@~Faaadly_";
        $lines[] = "@~revaa";

        $this->summaryText = implode("\n", $lines);
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        $products = [];
        if (strlen($this->searchProduct) >= 2) {
            $products = Product::where('is_active', true)
                ->with(['activeVariants', 'activeDiscountTiers'])
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchProduct . '%')
                      ->orWhere('sku',  'like', '%' . $this->searchProduct . '%');
                })
                ->limit(15)
                ->get();
        }

        $outlets = [];
        if (strlen($this->searchOutlet) >= 1 && $this->selectedOutletId === null) {
            $outlets = Outlet::where('is_active', true)
                ->where('type', 'ruko')
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchOutlet . '%')
                      ->orWhere('city', 'like', '%' . $this->searchOutlet . '%');
                })
                ->limit(8)
                ->get();
        }

        return view('livewire.pos.auditor-pos', [
            'products' => $products,
            'outlets'  => $outlets,
        ])->layout('layouts.app');
    }
}