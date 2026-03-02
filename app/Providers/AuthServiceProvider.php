<?php

namespace App\Providers;

use App\Models\StockTransfer;
use App\Models\Stock;
use App\Models\Audit;
use App\Policies\StockTransferPolicy;
use App\Policies\StockPolicy;
use App\Policies\AuditPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        StockTransfer::class => StockTransferPolicy::class,
        Stock::class => StockPolicy::class,
        Audit::class => AuditPolicy::class,
    ];

    public function boot(): void
    {
        // Global Gates
        Gate::define('access-all-outlets', function ($user) {
            return $user->isAdminPusat();
        });

        Gate::define('manage-users', function ($user) {
            return $user->isAdminPusat();
        });

        Gate::define('manage-audit', function ($user) {
            return $user->isAuditor() || $user->isAdminPusat();
        });
        Gate::define('view-audit', function ($user) {
            return $user->isAuditor() || $user->isAdminPusat() || $user->isKepalaRuko();
        });
        Gate::define('create-pegadaian', function ($user) {
            return $user->isPegadaian();
        });
        Gate::define('manage-pegadaian', function ($user) {
            return $user->isPegadaian() || $user->isAdminPusat();
        });

        Gate::define('manage-ruko', function ($user) {
            return $user->isKepalaRuko();
        });

        Gate::define('create-transfer', function ($user) {
            return $user->isKepalaRuko() || $user->isAdminPusat();
        });

        Gate::define('receive-shipment', function ($user) {
            return $user->isKepalaRuko() || $user->isStaffGudang();
        });

        Gate::define('conduct-audit', function ($user) {
            return $user->isKepalaRuko() || $user->isAdminPusat();
        });

        Gate::define('manage-clean', function ($user) {
            return $user->isMeksikoClean();
        });
    }
}