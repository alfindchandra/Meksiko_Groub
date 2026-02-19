<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outlet extends Model
{
    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'phone',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function transfersFrom(): HasMany
    {
        return $this->hasMany(StockTransfer::class, 'from_outlet_id');
    }

    public function transfersTo(): HasMany
    {
        return $this->hasMany(StockTransfer::class, 'to_outlet_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRuko($query)
    {
        return $query->where('type', 'ruko');
    }
}