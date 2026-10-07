<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductCategoryController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // dashboard
    Route::controller(DashboardController::class)->group(function () {

        // Route::get('/', 'index');
        Route::get('/dashboard', 'index')->name('dashboard');

    });
});

// Brands
Route::middleware(['auth', 'role:admin|super-admin'])->group(function () {
    Route::controller(BrandController::class)->prefix('brand')->group(function () {

        Route::get('/', 'index')->name('brands');
        Route::get('/create', 'create')->name('brand.create');
        Route::post('/store', 'store')->name('brand.store');
        Route::get('/{id}/edit', 'edit')->name('brand.edit');
        Route::post('/{id}/update', 'update')->name('brand.update');
        Route::get('/{id}/delete', 'delete')->name('brand.delete');

        // Brand Analysis Routes
        Route::get('/brands/analysis',  'analysis')->name('brands.analysis');
        Route::get('/brands/analysis/{id}',  'getBrandAnalysis')->name('brands.analysis.detail');
        Route::get('/brands/analysis/export',  'exportAnalysis')->name('brands.analysis.export');
        Route::get('/brands/top-brands',  'getTopBrands')->name('brands.top');
        Route::post('/brands/compare',  'compareBrands')->name('brands.compare');

    });
});

// product category
Route::middleware(['auth', 'role:admin|super-admin|store-manager'])->group(function () {
    Route::controller(ProductCategoryController::class)->prefix('category')->group(function () {

        Route::get('/', 'index')->name('categories');
        Route::get('/create', 'create')->name('category.create');
        Route::post('/store', 'store')->name('category.store');
        Route::get('/{id}/edit', 'edit')->name('category.edit');
        Route::post('/{id}/update', 'update')->name('category.update');
        Route::get('/{id}/delete', 'delete')->name('category.delete');

    });
});

// Product
Route::middleware(['auth', 'role:admin|super-admin|store-manager'])->group(function () {
    Route::controller(ProductController::class)->prefix('product')->group(function () {

        Route::get('/', 'index')->name('products');
        Route::get('/create', 'create')->name('product.create');
        Route::post('/store', 'store')->name('product.store');
        Route::get('/{id}/edit', 'edit')->name('product.edit');
        Route::post('/{id}/update', 'update')->name('product.update');
        Route::get('/{id}/delete', 'deleteImage')->name('product.image.delete');
        Route::get('/{id}', 'details')->name('product.details');

        // Product Analysis Routes
        Route::get('/products/analysis', 'analysis')->name('products.analysis');
        Route::get('/products/{id}/analysis', 'getProductAnalysis')->name('products.analysis.detail');
        Route::get('/products/analysis/export', 'exportAnalysis')->name('products.analysis.export');
        Route::get('/products/profit-loss-comparison', 'profitLossComparison')->name('products.profit-loss');

    });
});

// Suppliers
Route::middleware(['auth', 'role:admin|super-admin|store-manager'])->group(function () {
    Route::controller(SupplierController::class)->group(function () {
        Route::get('/suppliers', 'index')->name('suppliers.index');
        Route::get('/supplier/{supplier}', 'show')->name('suppliers.show');
        Route::post('/supplier', 'store')->name('suppliers.store');
        Route::put('/supplier/{supplier}', 'update')->name('suppliers.update');
        Route::delete('/supplier/{supplier}', 'destroy')->name('suppliers.destroy');
    });
});

// supply
Route::middleware(['auth', 'role:admin|super-admin|store-manager'])->group(function () {
    Route::controller(SupplyController::class)->group(function () {

        // Product Supplies Routes
        Route::get('/supplies', 'index')->name('supplies.index');
        Route::post('/supplies/store', 'store')->name('supplies.store');
        Route::get('/supplies/{id}/show', 'show')->name('supplies.show');
        Route::post('/supplies/{id}/approve', 'approve')->name('supplies.approve');
        Route::post('/supplies/{id}/cancel', 'cancel')->name('supplies.cancel');

        // Additional Routes
        Route::get('/supplies/supplier/{supplierId}', 'getBySupplier');
        Route::get('/supplies/product/{productId}', 'getByProduct');

        // Supply Analysis Routes
        Route::get('/supply/analysis', 'analysis')->name('supply.analysis');
        Route::get('/supply/analysis/{id}', 'getProductSupplyAnalysis')->name('supply.analysis.detail');
        Route::get('/supply/analysis/export', 'exportAnalysis')->name('supply.analysis.export');
        Route::get('/supply/low-stock', 'getLowStockProducts')->name('supply.low-stock');
        Route::get('/supply/reorder-needed', 'getReorderProducts')->name('supply.reorder');

    });
});


// sale
Route::middleware(['auth', 'role:admin|super-admin|cashier'])->group(function () {
    Route::controller(SaleController::class)->group(function () {
        // POS Routes
        Route::get('/pos', 'index')->name('pos.index');
        Route::post('/pos/sale', 'CreateSale')->name('pos.create');
        Route::get('/pos/receipt/{receiptNumber}', 'GenerateReceipt')->name('pos.receipt');

        // Sales Management
        Route::get('/sales', 'AllSales')->name('sales.index');
        Route::get('/sales/{id}', 'SaleDetails')->whereNumber('id')->name('sales.details');
        Route::post('/sales/refund', 'RefundSale')->name('sales.refund');

        // AJAX Endpoints
        Route::get('/search/products', 'SearchProducts')->name('search.products');
        Route::get('/api/products/{id}', 'GetProduct');

        // Reports
        Route::get('/reports/daily-sales', 'DailySalesReport')->name('reports.daily');
    });
});

// user/staff
Route::middleware(['auth', 'role:admin|super-admin'])->group(function () {
    Route::controller(AdminController::class)->group(function () {

        Route::get('/users', 'index')->name('users.index');
        Route::get('/users/create', 'create')->name('users.create');
        Route::post('/users', 'store')->name('users.store');
        Route::get('/users/{id}', 'show')->name('users.show');
        Route::get('/users/{id}/edit', 'edit')->name('users.edit');
        Route::post('/users/{id}/update', 'update')->name('users.update');
        Route::post('/users/{id}/del', 'destroy')->name('users.destroy');

        // Additional User Actions
        Route::post('/users/{id}/toggle-status', 'toggleStatus')->name('users.toggle-status');
        Route::post('/users/{id}/change-role', 'changeRole')->name('users.change-role');
        Route::get('/users/role/{roleName}', 'getUsersByRole')->name('users.by-role');


    });
});


require __DIR__ . '/auth.php';
