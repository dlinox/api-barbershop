
<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Inventory\Http\Controllers\CategoryController;
use App\Modules\Administrator\Inventory\Http\Controllers\BrandController;
use App\Modules\Administrator\Inventory\Http\Controllers\SupplierController;
use App\Modules\Administrator\Inventory\Http\Controllers\ProductController;
use App\Modules\Administrator\Inventory\Http\Controllers\ProductPresentationController;
use App\Modules\Administrator\Inventory\Http\Controllers\StockController;
use App\Modules\Administrator\Inventory\Http\Controllers\KardexController;
use App\Modules\Administrator\Inventory\Http\Controllers\PurchaseOrderController;
use App\Modules\Administrator\Inventory\Http\Controllers\SaleController;

Route::middleware(['auth:api'])->prefix('/inventory-categories')->group(function () {
    Route::post('/data-table', [CategoryController::class, 'dataTable'])->name('inventory-categories.dataTable');
    Route::post('/save', [CategoryController::class, 'save'])->name('inventory-categories.save');
    Route::get('/select-items', [CategoryController::class, 'selectItems'])->name('inventory-categories.selectItems');
    Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('inventory-categories.delete');
});

Route::middleware(['auth:api'])->prefix('/inventory-brands')->group(function () {
    Route::post('/data-table', [BrandController::class, 'dataTable'])->name('inventory-brands.dataTable');
    Route::post('/save', [BrandController::class, 'save'])->name('inventory-brands.save');
    Route::get('/select-items', [BrandController::class, 'selectItems'])->name('inventory-brands.selectItems');
    Route::delete('/delete/{id}', [BrandController::class, 'delete'])->name('inventory-brands.delete');
});

Route::middleware(['auth:api'])->prefix('/inventory-suppliers')->group(function () {
    Route::post('/data-table', [SupplierController::class, 'dataTable'])->name('inventory-suppliers.dataTable');
    Route::post('/save', [SupplierController::class, 'save'])->name('inventory-suppliers.save');
    Route::get('/select-items', [SupplierController::class, 'selectItems'])->name('inventory-suppliers.selectItems');
    Route::delete('/delete/{id}', [SupplierController::class, 'delete'])->name('inventory-suppliers.delete');
});

Route::middleware(['auth:api'])->prefix('/inventory-products')->group(function () {
    Route::post('/data-table', [ProductController::class, 'dataTable'])->name('inventory-products.dataTable');
    Route::post('/save', [ProductController::class, 'save'])->name('inventory-products.save');
    Route::get('/select-items', [ProductController::class, 'selectItems'])->name('inventory-products.selectItems');
    Route::delete('/delete/{id}', [ProductController::class, 'delete'])->name('inventory-products.delete');
});

Route::middleware(['auth:api'])->prefix('/inventory-product-presentations')->group(function () {
    Route::post('/data-table', [ProductPresentationController::class, 'dataTable'])->name('inventory-product-presentations.dataTable');
    Route::post('/save', [ProductPresentationController::class, 'save'])->name('inventory-product-presentations.save');
    Route::get('/select-items/{productId}', [ProductPresentationController::class, 'selectItems'])->name('inventory-product-presentations.selectItems');
    Route::delete('/delete/{id}', [ProductPresentationController::class, 'delete'])->name('inventory-product-presentations.delete');
});

Route::middleware(['auth:api'])->prefix('/inventory-stocks')->group(function () {
    Route::post('/data-table/{infrastructureId}', [StockController::class, 'dataTable'])->name('inventory-stocks.dataTable');
    Route::post('/initialize', [StockController::class, 'initializeStock'])->name('inventory-stocks.initializeStock');
    Route::post('/adjust', [StockController::class, 'adjustStock'])->name('inventory-stocks.adjustStock');
});

Route::middleware(['auth:api'])->prefix('/inventory-kardex')->group(function () {
    Route::post('/data-table', [KardexController::class, 'dataTable'])->name('inventory-kardex.dataTable');
    Route::post('/register-movement', [KardexController::class, 'registerMovement'])->name('inventory-kardex.registerMovement');
    Route::get('/by-presentation/{presentationId}/{branchId}', [KardexController::class, 'getByPresentation'])->name('inventory-kardex.getByPresentation');
});

Route::middleware(['auth:api'])->prefix('/inventory-purchase-orders')->group(function () {
    Route::post('/data-table', [PurchaseOrderController::class, 'dataTable'])->name('inventory-purchase-orders.dataTable');
    Route::post('/save', [PurchaseOrderController::class, 'save'])->name('inventory-purchase-orders.save');
    Route::get('/get/{id}', [PurchaseOrderController::class, 'getById'])->name('inventory-purchase-orders.getById');
    Route::post('/receive/{id}', [PurchaseOrderController::class, 'receiveOrder'])->name('inventory-purchase-orders.receiveOrder');
    Route::post('/cancel/{id}', [PurchaseOrderController::class, 'cancelOrder'])->name('inventory-purchase-orders.cancelOrder');
});

Route::middleware(['auth:api'])->prefix('/inventory-sales')->group(function () {
    Route::post('/data-table', [SaleController::class, 'dataTable'])->name('inventory-sales.dataTable');
    Route::get('/products/{infrastructureId}', [SaleController::class, 'getProducts'])->name('inventory-sales.getProducts');
    Route::post('/save', [SaleController::class, 'save'])->name('inventory-sales.save');
    Route::delete('/delete/{id}', [SaleController::class, 'delete'])->name('inventory-sales.delete');
    Route::post('/annul/{id}', [SaleController::class, 'annul'])->name('inventory-sales.annul');
});
