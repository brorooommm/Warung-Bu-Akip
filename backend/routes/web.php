<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CashierReceiptController;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Register page
Route::get('/', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/', [AuthController::class, 'register'])->name('register.submit');
//Login Page
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



/*
|--------------------------------------------------------------------------
| DASHBOARD ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/owner/dashboard', [OwnerController::class, 'index'])
    ->name('owner.dashboard');

Route::get('/cashier/transaction', function () {
    return view('cashier.transaction');
})->name('cashier.transaction');


Route::get('/admin', [ProductController::class, 'adminDashboard'])->name('admin');

Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('laporanAdmin');

Route::get('/laporan/export/pdf', [ProductController::class, 'exportPdf'])->name('laporan.export.pdf');


Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');


Route::get('/admin/performancecategory', [ProductController::class, 'performanceCategory'])
    ->name('perform');

Route::get('/penjualan', [LaporanController::class, 'index2'])->name('laporanowner');
Route::get('/owner/products/export/pdf', [OwnerController::class, 'exportPDF'])
    ->name('owner.products.export.pdf');


Route::get('owner/products', [OwnerController::class, 'products'])->name('owner.products');
Route::patch('owner/products/{product}', [OwnerController::class, 'updateProduct'])->name('owner.products.update');
Route::delete('owner/products/{product}', [OwnerController::class, 'deleteProduct'])->name('owner.products.delete');


/*
|--------------------------------------------------------------------------
| ADMIN PRODUCT ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin/products')->group(function () {

    Route::get('/', [ProductController::class, 'index'])->name('products.index');

    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');

    Route::get('/stock', [ProductController::class, 'manageStock'])->name('manage.stock');
    Route::patch('/stock/{id}', [ProductController::class, 'updateStock'])->name('products.updateStock');

    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');

    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Convert package to retail stock
});



/*
|--------------------------------------------------------------------------
| CASHIER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('cashier')->name('cashier.')->group(function () {

    Route::get('/products', [CashierController::class, 'products'])->name('products');

    Route::get('/expired', [CashierController::class, 'showExpired'])->name('expired');
    Route::post('/expired/update', [CashierController::class, 'updateExpired'])->name('expired.update');

    Route::get('/transaction', [CashierController::class, 'showTransaction'])->name('transaction');
    Route::post('/transaction/store', [CashierController::class, 'storeTransaction'])->name('transaction.store');

    Route::get('/settings', [CashierController::class, 'settings'])->name('settings');
});
// web.php
Route::get('/cashier/receipt/download', [CashierReceiptController::class, 'downloadLatest'])
    ->name('cashier.receipt.download');




