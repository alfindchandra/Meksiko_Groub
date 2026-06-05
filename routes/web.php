<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\Reports\SalesReport;
use App\Livewire\Admin\Reports\InventoryReport;
use App\Livewire\Admin\Reports\PawnReport;
use App\Livewire\Admin\Reports\ComparisonReport;
use App\Livewire\Admin\Reports\CleanReport;
use App\Livewire\Admin\GoodsReceipt;
use App\Livewire\Outlet\Dashboard as OutletDashboard;
use App\Livewire\Auditor\AuditorDashboard;
use App\Livewire\Stock\StockList;
use App\Livewire\Stock\LowStockAlert;
use App\Livewire\Stock\StockDetail;
use App\Livewire\Transfer\TransferList;
use App\Livewire\Transfer\SendTransfer;
use App\Livewire\Transfer\CreateTransfer;
use App\Livewire\Transfer\ApproveTransfer;
use App\Livewire\Transfer\ReceiveTransfer;
use App\Livewire\Shipment\ShipmentList;
use App\Livewire\Audit\AuditList;
use App\Livewire\Audit\CreateAudit;
use App\Livewire\Pegadaian\PegadaianDashboard;
use App\Livewire\Pegadaian\PawnList;
use App\Livewire\Pegadaian\CreatePawn;
use App\Livewire\Pegadaian\PawnDetail;
use App\Livewire\Sales\SalesList;
use App\Livewire\Pos\PointOfSale;
use App\Livewire\Pos\AuditorPos;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth', 'verified'])->group(function () {

    // POS
    Route::get('/pos', PointOfSale::class)->name('pos');
    Route::get('/pos/receipt/{sale}', function ($id) {
        return view('pos.receipt', ['saleId' => $id]);
    })->name('pos.receipt');

    // Auditor POS
    Route::get('/auditor/pos', AuditorPos::class)->name('auditor.pos');
    Route::get('/auditor/pos/receipt/{saleId}', function ($saleId) {
        return view('pos.receipt', ['saleId' => $saleId]);
    })->name('auditor.pos.receipt');

    // Admin
    Route::middleware(['can:manage-users'])->group(function () {
        Route::get('/admin/goods-receipt', GoodsReceipt::class)->name('admin.goods-receipt');
    });

    // Pegadaian Routes
    Route::prefix('pegadaian')->name('pegadaian.')->group(function () {
        Route::get('/dashboard', PegadaianDashboard::class)->name('dashboard');
        Route::get('/', PawnList::class)->name('list');
        Route::get('/create', CreatePawn::class)->name('create');
        Route::get('/{pawnId}', PawnDetail::class)->name('detail');
        Route::get('/{pawnId}/receipt', function ($pawnId) {
            return view('pegadaian.receipt', ['pawnId' => $pawnId]);
        })->name('receipt');
    });

    // Central Dashboard Redirector
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->isAdminPusat()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isMeksikoClean()) {
            return redirect()->route('meksikoclean.dashboard');
        }
        if ($user->isPegadaian()) {
            return redirect()->route('pegadaian.dashboard');
        }
        if ($user->isAuditor()) {
            return redirect()->route('auditor.dashboard');
        }

        return redirect()->route('outlet.dashboard');
    })->name('dashboard');

    // Meksiko Clean Routes
    Route::prefix('meksiko-clean')->name('meksikoclean.')->group(function () {
        Route::get('/dashboard', \App\Livewire\MeksikoClean\Dashboard::class)->name('dashboard');
        Route::get('/services', \App\Livewire\MeksikoClean\ServiceList::class)->name('services.index');
        Route::get('/partners', \App\Livewire\MeksikoClean\PartnerList::class)->name('partners.index');
        Route::get('/transactions', \App\Livewire\MeksikoClean\TransactionList::class)->name('transactions.index');
        Route::get('/transactions/create', \App\Livewire\MeksikoClean\CreateTransaction::class)->name('transactions.create');
    });

    // Global / Common Routes
    Route::get('/auditor/dashboard', AuditorDashboard::class)->name('auditor.dashboard');
    Route::get('/notifications', [DashboardController::class, 'adminNotifications'])->name('notifications.all');
    Route::get('/sales', SalesList::class)->name('sales.list');
    Route::get('/outlet/dashboard', OutletDashboard::class)->name('outlet.dashboard');

    // Admin Specific Routes
    Route::get('/admin/dashboard', AdminDashboard::class)
        ->middleware('can:access-all-outlets')
        ->name('admin.dashboard');

    Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.reports.sales');
        })->name('index');

        Route::get('/sales', SalesReport::class)->name('sales');
        Route::get('/inventory', InventoryReport::class)->name('inventory');
        Route::get('/pawn', PawnReport::class)->name('pawn');
        Route::get('/comparison', ComparisonReport::class)->name('comparison');
        Route::get('/clean', CleanReport::class)->name('clean');
    });

    // Stock Management Routes
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/list', StockList::class)->name('list');
        Route::get('/low-stock', LowStockAlert::class)->name('low-stock');
        Route::get('/{id}', StockDetail::class)->name('detail');
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

        Route::middleware('can:manage-audit')->group(function () {
            Route::get('/pending', function () {
                return view('transfer.pending');
            })->name('pending');
            Route::get('/approve/{transfer}', ApproveTransfer::class)->name('approve');
        });

        Route::get('/receive/{transfer}', ReceiveTransfer::class)->name('receive');
    });

    // Shipment Routes
    Route::prefix('shipment')->name('shipment.')->group(function () {
        Route::get('/list', ShipmentList::class)->name('list');
    });

    // Audit Routes
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::middleware('can:manage-audit')->group(function () {
            Route::get('/list', AuditList::class)->name('list');
            Route::get('/create', CreateAudit::class)->name('create');
        });
        Route::middleware('can:view-audit')->group(function () {
            Route::get('/payment', \App\Livewire\Audit\AuditPayment::class)->name('payment');
        });
    });

    // Admin Data Management
    Route::prefix('admin')->name('admin.')->middleware('can:manage-users')->group(function () {
        Route::get('/users', UserManagement::class)->name('data.users');
        Route::get('/audit-payments', \App\Livewire\Admin\AuditPaymentConfirmation::class)->name('data.audit-payments');
        Route::get('/products', \App\Livewire\Admin\ProductManagement::class)->name('data.products');
        Route::get('/outlets', \App\Livewire\Admin\OutletManagement::class)->name('data.outlets');
        Route::get('/pending-transfers', function () {
            return view('admin.pending-transfers');
        })->name('pending-transfers');
    });

    // Profile Routes
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__ . '/auth.php';