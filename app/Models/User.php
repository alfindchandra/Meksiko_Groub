<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'outlet_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Helper methods
    public function hasRole(string $roleName): bool
    {
        return $this->role->name === $roleName;
    }

    public function isAdminPusat(): bool
    {
        return $this->role->isAdminPusat();
    }

    public function isKepalaRuko(): bool
    {
        return $this->role->isKepalaRuko();
    }

    public function isAuditor(): bool
    {
        return $this->role->isAuditor();
    }
    public function isPegadaian(): bool
{
    return $this->role->isPegadaian();
}

    public function canAccessOutlet(int $outletId): bool
    {
        return $this->isAdminPusat() || $this->outlet_id === $outletId;
    }
}