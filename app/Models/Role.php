<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'display_name', 'description'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Helper methods
    public function isAdminPusat(): bool
    {
        return $this->name === 'admin_pusat';
    }

    public function isKepalaRuko(): bool
    {
        return $this->name === 'kepala_ruko';
    }

    public function isStaffGudang(): bool
    {
        return $this->name === 'staff_gudang';
    }
}