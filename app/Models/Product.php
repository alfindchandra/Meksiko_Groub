<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'description',
        'category_id',
        'unit',
        'price',
        'min_stock',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper: Get stock for specific outlet
    public function getStockForOutlet(int $outletId): ?Stock
    {
        return $this->stocks()->where('outlet_id', $outletId)->first();
    }
    public function discountTiers(): HasMany
{
    return $this->hasMany(ProductDiscountTier::class);
}

public function activeDiscountTiers(): HasMany
{
    return $this->hasMany(ProductDiscountTier::class)
        ->where('is_active', true)
        ->orderBy('min_quantity', 'asc');
}

public function variants(): HasMany
{
    return $this->hasMany(ProductVariant::class);
}

public function activeVariants(): HasMany
{
    return $this->hasMany(ProductVariant::class)
        ->where('is_active', true)
        ->orderBy('sort_order', 'asc');
}
}