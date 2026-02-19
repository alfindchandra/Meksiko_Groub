<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Stock;

class StockPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Stock $stock): bool
    {
        return $user->canAccessOutlet($stock->outlet_id);
    }

    public function update(User $user, Stock $stock): bool
    {
        // Hanya admin atau kepala ruko dari outlet tersebut
        return $user->isAdminPusat() || 
               ($user->isKepalaRuko() && $user->outlet_id === $stock->outlet_id);
    }

    public function adjustStock(User $user, Stock $stock): bool
    {
        return $this->update($user, $stock);
    }
}