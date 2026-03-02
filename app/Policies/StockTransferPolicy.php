<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StockTransfer;

class StockTransferPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Semua user bisa melihat transfer
    }

    public function view(User $user, StockTransfer $transfer): bool
    {
        // Admin pusat bisa lihat semua
        if ($user->isAdminPusat()) {
            return true;
        }

        // User lain hanya bisa lihat transfer yang relevan dengan outlet mereka
        return $transfer->from_outlet_id === $user->outlet_id 
            || $transfer->to_outlet_id === $user->outlet_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdminPusat() || $user->isKepalaRuko();
    }

    public function approve(User $user, StockTransfer $transfer): bool
    {
        if (!$transfer->canBeApproved()) {
            return false;
        }

        // Admin pusat bisa approve semua
        if ($user->isAdminPusat()) {
            return true;
        }

        // Kepala ruko hanya bisa approve jika transfer masuk ke outlet mereka
        return $user->isKepalaRuko() && $transfer->to_outlet_id === $user->outlet_id;
    }

    public function send(User $user, StockTransfer $transfer): bool
    {
        if (!$transfer->canBeSent()) {
            return false;
        }

        // Admin bisa kirim semua
        if ($user->isAdminPusat()) {
            return true;
        }

        // Hanya Kepala Ruko dari outlet pengirim yang bisa mengirim
        return $user->isKepalaRuko() && $user->outlet_id === $transfer->from_outlet_id;
    }

    public function receive(User $user, StockTransfer $transfer): bool
    {
        if (!$transfer->canBeReceived()) {
            return false;
        }

        // Admin bisa terima semua
        if ($user->isAdminPusat()) {
            return true;
        }

        // Hanya Kepala Ruko dari outlet penerima yang bisa menerima
        return $user->isKepalaRuko() && $user->outlet_id === $transfer->to_outlet_id;
    }

    public function delete(User $user, StockTransfer $transfer): bool
    {
        // Hanya pending transfer yang bisa dihapus, dan hanya oleh pembuat atau admin
        return $transfer->isPending() && 
               ($user->isAdminPusat() || $transfer->requested_by === $user->id);
    }
}