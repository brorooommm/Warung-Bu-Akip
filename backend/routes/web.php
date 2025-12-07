<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\CashierReceiptController;
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Register page
Route::get('/', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/', [AuthController::class, 'register'])->name('register.submit');

// Login page
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('forgot.form');
Route::post('/forgot-password', [AuthController::class, 'sendForgotPassword'])->name('forgot.submit');
/*
|--------------------------------------------------------------------------
| OWNER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class.':owner'])->group(function () {
    Route::get('/owner/dashboard', [OwnerController::class, 'index'])->name('owner.dashboard');
    Route::get('/owner/keuangan', [LaporanController::class, 'index2'])->name('laporanowner');
    Route::get('/owner/products/export/pdf', [OwnerController::class, 'exportPDF'])->name('owner.products.export.pdf');
    Route::get('/owner/products', [OwnerController::class, 'products'])->name('owner.products');
    Route::get('/owner/products/create', [OwnerController::class, 'create'])->name('owner.products.create');
    Route::post('/owner/products', [OwnerController::class, 'store'])->name('owner.products.store');
    Route::patch('/owner/products/{product}', [OwnerController::class, 'updateProduct'])->name('owner.products.update');
    Route::delete('/owner/products/{product}', [OwnerController::class, 'deleteProduct'])->name('owner.products.delete');
    Route::get('/owner/damage', [StockMovementController::class, 'create'])->name('owner.damage');
    Route::post('/owner/damage', [StockMovementController::class, 'store'])->name('owner.damage.store');
});


/*
|--------------------------------------------------------------------------
| CASHIER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class.':cashier'])->prefix('cashier')->name('cashier.')->group(function () {
     Route::get('/receipt/download', [CashierReceiptController::class, 'downloadLatest'])->name('receipt.download');
    Route::get('/transaction', [CashierController::class, 'showTransaction'])->name('transaction');
    Route::post('/transaction/store', [CashierController::class, 'storeTransaction'])->name('transaction.store');
    Route::get('/settings', [CashierController::class, 'settings'])->name('settings');
});
Route::get('/test', function() {
    return class_exists(\App\Http\Middleware\RoleMiddleware::class);
});
Route::get('/test2', function(\Illuminate\Http\Request $request){
    $middleware = app(\App\Http\Middleware\RoleMiddleware::class);
    return get_class($middleware);
});
Route::get('/tes-role', function(){
    return 'RoleMiddleware works!';
})->middleware('role:admin');



/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class.':admin'])->group(function () {
    Route::get('/admin', [ProductController::class, 'adminDashboard'])->name('admin');
    Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('laporanAdmin');
    Route::get('/laporan/export/pdf', [ProductController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/admin/performancecategory', [ProductController::class, 'performanceCategory'])->name('perform');
    Route::get('/admin/history', [ProductController::class, 'riwayatTransaksi'])->name('admin.history');
    Route::get('/admin/settings', [AuthController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings/update-password', [AuthController::class, 'updatePassword'])->name('admin.settings.updatePassword');
    Route::prefix('admin/products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/stock', [ProductController::class, 'manageStock'])->name('manage.stock');
        Route::patch('/stock/{id}', [ProductController::class, 'updateStock'])->name('products.updateStock');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');
    });
});
