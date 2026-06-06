<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductDiscountTier;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManagement extends Component
{
    use WithPagination;

    // ─── Filter ───────────────────────────────────────────────────────────────
    public $search = '';
    public $categoryFilter = '';

    // ─── Modal Produk ─────────────────────────────────────────────────────────
    public $showModal  = false;
    public $editMode   = false;
    public $editingId  = null;

    public $sku         = '';
    public $name        = '';
    public $description = '';
    public $category_id = '';
    public $unit        = 'btl';
    public $price       = 0;
    public $min_stock   = 0;
    public $is_active   = true;

    // ─── Modal Diskon ─────────────────────────────────────────────────────────
    public $showDiscountModal        = false;
    public $selectedProductForDiscount = null;
    public $discountTiers = [];

    // ─── Modal Varian ─────────────────────────────────────────────────────────
    public $showVariantModal         = false;
    public $selectedProductForVariant = null;
    public $variants = [];

    // Form varian
    public $variantUnitName  = '';
    public $variantPrice     = '';
    public $variantSortOrder = 0;
    public $editingVariantId = null;

    protected $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return [
            'sku'         => 'required|string|max:50|unique:products,sku,' . ($this->editingId ?? 'NULL'),
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit'        => 'required|string|max:20',
            'price'       => 'required|numeric|min:0',
            'min_stock'   => 'integer|min:0',
        ];
    }

    public function updatingSearch()    { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }

    // ─── Produk CRUD ──────────────────────────────────────────────────────────

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode  = false;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->editingId   = $id;
        $this->sku         = $product->sku;
        $this->name        = $product->name;
        $this->description = $product->description ?? '';
        $this->category_id = $product->category_id;
        $this->unit        = $product->unit;
        $this->price       = $product->price;
        $this->min_stock   = $product->min_stock;
        $this->is_active   = $product->is_active;
        $this->editMode    = true;
        $this->showModal   = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'sku'         => strtoupper(trim($this->sku)),
            'name'        => trim($this->name),
            'description' => trim($this->description) ?: null,
            'category_id' => $this->category_id,
            'unit'        => $this->unit,
            'price'       => (float) $this->price,
            'min_stock'   => (int) $this->min_stock,
            'is_active'   => $this->is_active,
        ];

        if ($this->editMode && $this->editingId) {
            Product::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Produk berhasil diperbarui.']);
        } else {
            Product::create($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Produk berhasil ditambahkan.']);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Status produk diperbarui.']);
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Produk dihapus.']);
    }

    private function resetForm()
    {
        $this->editingId   = null;
        $this->editMode    = false;
        $this->sku         = '';
        $this->name        = '';
        $this->description = '';
        $this->category_id = '';
        $this->unit        = 'btl';
        $this->price       = 0;
        $this->min_stock   = 0;
        $this->is_active   = true;
        $this->resetValidation();
    }

    // ─── Discount Modal ───────────────────────────────────────────────────────

    public function openDiscountModal($productId)
    {
        $this->selectedProductForDiscount = Product::find($productId);
        $this->discountTiers = ProductDiscountTier::where('product_id', $productId)
            ->orderBy('min_quantity')
            ->get()
            ->map(fn($t) => [
                'id'                  => $t->id,
                'min_quantity'        => $t->min_quantity,
                'discount_percentage' => $t->discount_percentage,
                'is_active'           => $t->is_active,
            ])
            ->toArray();

        $this->showDiscountModal = true;
    }

    public function closeDiscountModal()
    {
        $this->showDiscountModal        = false;
        $this->selectedProductForDiscount = null;
        $this->discountTiers = [];
    }

    public function addDiscountTier()
    {
        $this->discountTiers[] = [
            'id'                  => null,
            'min_quantity'        => 1,
            'discount_percentage' => 0,
            'is_active'           => true,
        ];
    }

    public function removeDiscountTier($index)
    {
        $tier = $this->discountTiers[$index];
        if ($tier['id']) {
            ProductDiscountTier::find($tier['id'])?->delete();
        }
        unset($this->discountTiers[$index]);
        $this->discountTiers = array_values($this->discountTiers);
    }

    public function saveDiscounts()
    {
        if (!$this->selectedProductForDiscount) return;

        foreach ($this->discountTiers as $tier) {
            if ($tier['id']) {
                ProductDiscountTier::find($tier['id'])?->update([
                    'min_quantity'        => (int) $tier['min_quantity'],
                    'discount_percentage' => (float) $tier['discount_percentage'],
                    'is_active'           => (bool) $tier['is_active'],
                ]);
            } else {
                ProductDiscountTier::create([
                    'product_id'          => $this->selectedProductForDiscount->id,
                    'min_quantity'        => (int) $tier['min_quantity'],
                    'discount_percentage' => (float) $tier['discount_percentage'],
                    'is_active'           => (bool) $tier['is_active'],
                ]);
            }
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Diskon berhasil disimpan.']);
        $this->closeDiscountModal();
    }

    // ─── Variant Modal ────────────────────────────────────────────────────────

    public function openVariantModal($productId)
    {
        $this->selectedProductForVariant = Product::find($productId);
        $this->loadVariants();
        $this->resetVariantForm();
        $this->showVariantModal = true;
    }

    public function closeVariantModal()
    {
        $this->showVariantModal          = false;
        $this->selectedProductForVariant = null;
        $this->variants = [];
        $this->resetVariantForm();
    }

    private function loadVariants()
    {
        if (!$this->selectedProductForVariant) return;

        $this->variants = ProductVariant::where('product_id', $this->selectedProductForVariant->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->toArray();
    }

    public function saveVariant()
    {
        $this->validate([
            'variantUnitName'  => 'required|string|max:50',
            'variantPrice'     => 'required|numeric|min:0',
            'variantSortOrder' => 'integer|min:0',
        ], [
            'variantUnitName.required' => 'Nama satuan wajib diisi.',
            'variantPrice.required'    => 'Harga wajib diisi.',
        ]);

        $productId = $this->selectedProductForVariant->id;

        if ($this->editingVariantId) {
            // Update
            ProductVariant::where('id', $this->editingVariantId)
                ->where('product_id', $productId)
                ->update([
                    'unit_name'  => trim($this->variantUnitName),
                    'price'      => (float) $this->variantPrice,
                    'sort_order' => (int) $this->variantSortOrder,
                ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Varian diperbarui.']);
        } else {
            // Cek duplikat
            $exists = ProductVariant::where('product_id', $productId)
                ->where('unit_name', trim($this->variantUnitName))
                ->exists();

            if ($exists) {
                $this->addError('variantUnitName', 'Satuan "' . $this->variantUnitName . '" sudah ada.');
                return;
            }

            ProductVariant::create([
                'product_id' => $productId,
                'unit_name'  => trim($this->variantUnitName),
                'price'      => (float) $this->variantPrice,
                'sort_order' => (int) $this->variantSortOrder,
                'is_active'  => true,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Varian ditambahkan.']);
        }

        $this->resetVariantForm();
        $this->loadVariants();
    }

    public function editVariant($variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $this->editingVariantId  = $variant->id;
        $this->variantUnitName   = $variant->unit_name;
        $this->variantPrice      = $variant->price;
        $this->variantSortOrder  = $variant->sort_order;
    }

    public function toggleVariantActive($variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $variant->update(['is_active' => !$variant->is_active]);
        $this->loadVariants();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Status varian diperbarui.']);
    }

    public function deleteVariant($variantId)
    {
        ProductVariant::where('id', $variantId)
            ->where('product_id', $this->selectedProductForVariant->id)
            ->delete();

        $this->loadVariants();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Varian dihapus.']);
    }

    public function cancelEditVariant()
    {
        $this->resetVariantForm();
    }

    private function resetVariantForm()
    {
        $this->editingVariantId  = null;
        $this->variantUnitName   = '';
        $this->variantPrice      = '';
        $this->variantSortOrder  = 0;
        $this->resetValidation(['variantUnitName', 'variantPrice', 'variantSortOrder']);
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        $products = Product::with(['category', 'activeVariants', 'activeDiscountTiers'])
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku',  'like', '%' . $this->search . '%')
            )
            ->when($this->categoryFilter, fn($q) =>
                $q->where('category_id', $this->categoryFilter)
            )
            ->orderBy('name')
            ->paginate(15);

        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.data.product-management', [
            'products'   => $products,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}