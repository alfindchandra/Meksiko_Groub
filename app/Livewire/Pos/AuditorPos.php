<?php

namespace App\Livewire\Pos;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Outlet;
use Livewire\Component;

class AuditorPos extends Component
{
    // Outlet & Cabang Search
    public $searchOutlet = '';
    public $selectedOutletId = null; // Diubah dari string kosong ke null untuk deteksi reset input
    public $selectedOutletName = '';

    // Cart / Barang
    public $searchProduct = '';
    public $cart = [];

    // Konfirmasi Setoran
    public $nominalTitipan = 0;

    // Summary Text Output
    public $summaryText = '';
    
    // Variant Selection
    public $selectedVariantProductId = null;
    public $selectedVariantId = null;

    /**
     * Listener otomatis saat user memilih outlet dari dropdown/suggest list
     */
    public function selectOutlet($id)
    {
        $outlet = Outlet::find($id);
        if ($outlet) {
            $this->selectedOutletId = $outlet->id;
            $this->selectedOutletName = $outlet->name;
            // Mengisi field input dengan format nama dan kotanya
            $this->searchOutlet = $outlet->name . ' (' . $outlet->city . ')';
            
            // Perbarui teks laporan jika keranjang tidak kosong
            $this->generateSummary();
        }
    }

    /**
     * Menghapus outlet terpilih agar user bisa mencari ulang cabang lain
     */
    public function resetSelectedOutlet()
    {
        $this->selectedOutletId = null;
        $this->selectedOutletName = '';
        $this->searchOutlet = '';
        
        // Perbarui teks laporan jika keranjang tidak kosong
        $this->generateSummary();
    }

    /**
     * Menambahkan barang ke dalam daftar audit
     */
    public function addToCart($productId, $variantId = null)
    {
        $product = Product::with('activeVariants')->find($productId);
        if (!$product) return;

        // If product has variants and no variant selected, show variant selector
        if ($product->activeVariants->count() > 0 && !$variantId) {
            $this->selectedVariantProductId = $productId;
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

        foreach ($this->cart as $index => $item) {
            if ($item['cart_key'] === $cartKey) {
                $this->cart[$index]['quantity']++;
                $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['unit_price'];
                $this->generateSummary();
                return;
            }
        }

        $this->cart[] = [
            'cart_key'     => $cartKey,
            'product_id'   => (int)$product->id,
            'variant_id'   => $variantId,
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

    /**
     * Select variant dan add to cart
     */
    public function selectVariant($variantId)
    {
        if ($this->selectedVariantProductId) {
            $this->addToCart($this->selectedVariantProductId, $variantId);
            $this->selectedVariantProductId = null;
        }
    }

    /**
     * Mengeluarkan item dari daftar keranjang audit
     */
    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->generateSummary();
    }

    /**
     * Mengubah jumlah kuantitas barang laku
     */
    public function updateQuantity($index, $qty)
    {
        $qty = (int)$qty;
        if ($qty < 1) {
            $this->removeFromCart($index);
            return;
        }
        $this->cart[$index]['quantity'] = $qty;
        $this->cart[$index]['subtotal'] = $qty * $this->cart[$index]['unit_price'];
        $this->generateSummary();
    }

    /**
     * Listener saat nominal titipan fisik diubah lewat input pengetikan
     */
    public function updatedNominalTitipan()
    {
        $this->generateSummary();
    }

    /**
     * Computed Properties / Livewire Computed Properties (Subtotal)
     */
    public function getSubtotalProperty(): float
    {
        return (float) collect($this->cart)->sum('subtotal');
    }

    /**
     * Computed Properties (Kekurangan Setoran)
     */
    public function getKekuranganProperty(): float
    {
        $titipan = (float)($this->nominalTitipan ?: 0);
        return max(0, $this->subtotal - $titipan);
    }

    /**
     * Computed Properties (Kelebihan Setoran)
     */
    public function getLebihProperty(): float
    {
        $titipan = (float)($this->nominalTitipan ?: 0);
        return max(0, $titipan - $this->subtotal);
    }

    /**
     * Membuat atau memperbarui salinan draf teks laporan audit
     */
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
        $lines[] = "Tanggal: " . now()->format('d M Y H:i');
        $lines[] = str_repeat("-", 35);

        foreach ($this->cart as $i => $item) {
            $num = $i + 1;
            $lines[] = "{$num}. {$item['product_name']}";
            $lines[] = "      {$item['quantity']} {$item['unit']} x Rp " . number_format($item['unit_price'], 0, ',', '.');
            $lines[] = "       = Rp " . number_format($item['subtotal'], 0, ',', '.');
        }

        $lines[] = str_repeat("-", 35);
        $lines[] = "TOTAL  : Rp " . number_format($this->subtotal, 0, ',', '.');

        $titipan = (float)($this->nominalTitipan ?: 0);
        if ($titipan > 0) {
            $lines[] = "TITIPAN: Rp " . number_format($titipan, 0, ',', '.');
            if ($this->kekurangan > 0) {
                $lines[] = "KURANG : Rp " . number_format($this->kekurangan, 0, ',', '.');
            } elseif ($this->lebih > 0) {
                $lines[] = "LEBIH  : Rp " . number_format($this->lebih, 0, ',', '.');
            } else {
                $lines[] = "STATUS : LUNAS ✅";
            }
        }

        $this->summaryText = implode("\n", $lines);
    }

    public function render()
    {
        // 1. Logika Pencarian Produk
        $products = [];
        if (strlen($this->searchProduct) >= 2) {
            $products = Product::where('is_active', true)
                ->with(['activeVariants', 'activeDiscountTiers'])
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchProduct . '%')
                      ->orWhere('sku', 'like', '%' . $this->searchProduct . '%');
                })
                ->limit(15)
                ->get();
        }

        // 2. Logika Pencarian Cabang / Outlet (Hanya dicari jika ada input text & outlet belum dipilih)
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