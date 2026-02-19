<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Outlet\Dashboard as OutletDashboard;
use App\Livewire\Stock\StockList;
use App\Livewire\Stock\LowStockAlert;
use App\Livewire\Transfer\TransferList;
use App\Livewire\Transfer\SendTransfer;
use App\Livewire\Transfer\CreateTransfer;
use App\Livewire\Transfer\ApproveTransfer;
use App\Livewire\Transfer\ReceiveTransfer;
use App\Livewire\Shipment\ShipmentList;
use App\Livewire\Audit\AuditList;
use App\Livewire\Audit\CreateAudit;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Routes
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdminPusat()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('outlet.dashboard');
        }
    })->name('dashboard');

    Route::get('/notifications', [DashboardController::class, 'adminNotifications'])
        ->name('notifications.all');

    Route::get('/admin/dashboard', AdminDashboard::class)
        ->middleware('can:access-all-outlets')
        ->name('admin.dashboard');

    Route::get('/outlet/dashboard', OutletDashboard::class)
        ->name('outlet.dashboard');

    // Stock Management Routes
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/list', StockList::class)->name('list');
        Route::get('/low-stock', LowStockAlert::class)->name('low-stock');
    });

    // Transfer Routes
    Route::prefix('transfer')->name('transfer.')->group(function () {
        Route::get('/list', TransferList::class)->name('list');
        Route::get('/{transferId}/surat-jalan', function ($transferId) {
    $transfer = \App\Models\StockTransfer::with([
        'fromOutlet', 'toOutlet',
        'requestedBy', 'approvedBy', 'sentBy',
        'items.product.category',
        'shipment',
    ])->findOrFail($transferId);

    return view('transfer.surat-jalan', compact('transfer'));
})->middleware('auth')->name('surat-jalan');
        Route::middleware('can:create-transfer')->group(function () {
            Route::get('/create', CreateTransfer::class)->name('create');
        });
        
        Route::get('/detail/{transfer}', function ($id) {
            return view('transfer.detail', ['transferId' => $id]);
        })->name('detail');
        Route::get('/send/{transferId}', SendTransfer::class)->name('send');
        
        Route::middleware('can:approve-transfers')->group(function () {
            Route::get('/pending', function () {
                return view('transfer.pending');
            })->name('pending');
            Route::get('/approve/{transfer}', ApproveTransfer::class)->name('approve');
        });
        
        Route::get('/receive/{transfer}', ReceiveTransfer::class)
            ->name('receive');
    });

    // Shipment Routes
    Route::prefix('shipment')->name('shipment.')->group(function () {
        Route::get('/list', ShipmentList::class)->name('list');
    });

    // Audit Routes
    Route::prefix('audit')->name('audit.')->middleware('can:conduct-audit')->group(function () {
        Route::get('/list', AuditList::class)->name('list');
        Route::get('/create', CreateAudit::class)->name('create');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('can:manage-users')->group(function () {
         Route::get('/users', UserManagement::class)->name('data.users');
    Route::get('/products', \App\Livewire\Admin\ProductManagement::class)->name('data.products');
    Route::get('/outlets', \App\Livewire\Admin\OutletManagement::class)->name('data.outlets');


        Route::get('/pending-transfers', function () {
            return view('admin.pending-transfers');
        })->name('pending-transfers');
    });

    // Profile Routes
   Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
});

require __DIR__.'/auth.php';
