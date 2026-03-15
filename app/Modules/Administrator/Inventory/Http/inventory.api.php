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
    Route::post('/data-table', [CategoryController::class, 'dataTable'])->name('inventory-categories.dataTable')->middleware('permission:inventory.category.view');
    Route::post('/save', [CategoryController::class, 'save'])->name('inventory-categories.save')->middleware('permission:inventory.category.create,inventory.category.edit');
    Route::get('/select-items', [CategoryController::class, 'selectItems'])->name('inventory-categories.selectItems');
    Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('inventory-categories.delete')->middleware('permission:inventory.category.delete');
});

Route::middleware(['auth:api'])->prefix('/inventory-brands')->group(function () {
    Route::post('/data-table', [BrandController::class, 'dataTable'])->name('inventory-brands.dataTable')->middleware('permission:inventory.brand.view');
    Route::post('/save', [BrandController::class, 'save'])->name('inventory-brands.save')->middleware('permission:inventory.brand.create,inventory.brand.edit');
    Route::get('/select-items', [BrandController::class, 'selectItems'])->name('inventory-brands.selectItems');
    Route::delete('/delete/{id}', [BrandController::class, 'delete'])->name('inventory-brands.delete')->middleware('permission:inventory.brand.delete');
});

Route::middleware(['auth:api'])->prefix('/inventory-suppliers')->group(function () {
    Route::post('/data-table', [SupplierController::class, 'dataTable'])->name('inventory-suppliers.dataTable')->middleware('permission:inventory.supplier.view');
    Route::post('/save', [SupplierController::class, 'save'])->name('inventory-suppliers.save')->middleware('permission:inventory.supplier.create,inventory.supplier.edit');
    Route::get('/select-items', [SupplierController::class, 'selectItems'])->name('inventory-suppliers.selectItems');
    Route::delete('/delete/{id}', [SupplierController::class, 'delete'])->name('inventory-suppliers.delete')->middleware('permission:inventory.supplier.delete');
});

Route::middleware(['auth:api'])->prefix('/inventory-products')->group(function () {
    Route::post('/data-table', [ProductController::class, 'dataTable'])->name('inventory-products.dataTable')->middleware('permission:inventory.product.view');
    Route::post('/save', [ProductController::class, 'save'])->name('inventory-products.save')->middleware('permission:inventory.product.create,inventory.product.edit');
    Route::get('/select-items', [ProductController::class, 'selectItems'])->name('inventory-products.selectItems');
    Route::delete('/delete/{id}', [ProductController::class, 'delete'])->name('inventory-products.delete')->middleware('permission:inventory.product.delete');
});

Route::middleware(['auth:api'])->prefix('/inventory-product-presentations')->group(function () {
    Route::post('/data-table', [ProductPresentationController::class, 'dataTable'])->name('inventory-product-presentations.dataTable')->middleware('permission:inventory.product.view');
    Route::post('/save', [ProductPresentationController::class, 'save'])->name('inventory-product-presentations.save')->middleware('permission:inventory.product.create,inventory.product.edit');
    Route::get('/select-items/{productId}', [ProductPresentationController::class, 'selectItems'])->name('inventory-product-presentations.selectItems');
    Route::delete('/delete/{id}', [ProductPresentationController::class, 'delete'])->name('inventory-product-presentations.delete')->middleware('permission:inventory.product.delete');
});

Route::middleware(['auth:api'])->prefix('/inventory-stocks')->group(function () {
    Route::post('/data-table/{infrastructureId}', [StockController::class, 'dataTable'])->name('inventory-stocks.dataTable')->middleware('permission:inventory.stock.view');
    Route::post('/initialize', [StockController::class, 'initializeStock'])->name('inventory-stocks.initializeStock')->middleware('permission:inventory.stock.initialize');
    Route::post('/adjust', [StockController::class, 'adjustStock'])->name('inventory-stocks.adjustStock')->middleware('permission:inventory.stock.adjust');
});

Route::middleware(['auth:api'])->prefix('/inventory-kardex')->group(function () {
    Route::post('/data-table', [KardexController::class, 'dataTable'])->name('inventory-kardex.dataTable')->middleware('permission:inventory.kardex.view');
    Route::post('/register-movement', [KardexController::class, 'registerMovement'])->name('inventory-kardex.registerMovement')->middleware('permission:inventory.kardex.register_movement');
    Route::get('/by-presentation/{presentationId}/{branchId}', [KardexController::class, 'getByPresentation'])->name('inventory-kardex.getByPresentation')->middleware('permission:inventory.kardex.view');
});

Route::middleware(['auth:api'])->prefix('/inventory-purchase-orders')->group(function () {
    Route::post('/data-table', [PurchaseOrderController::class, 'dataTable'])->name('inventory-purchase-orders.dataTable')->middleware('permission:inventory.purchase_order.view');
    Route::post('/save', [PurchaseOrderController::class, 'save'])->name('inventory-purchase-orders.save')->middleware('permission:inventory.purchase_order.create');
    Route::get('/get/{id}', [PurchaseOrderController::class, 'getById'])->name('inventory-purchase-orders.getById')->middleware('permission:inventory.purchase_order.view');
    Route::post('/receive/{id}', [PurchaseOrderController::class, 'receiveOrder'])->name('inventory-purchase-orders.receiveOrder')->middleware('permission:inventory.purchase_order.receive');
    Route::post('/cancel/{id}', [PurchaseOrderController::class, 'cancelOrder'])->name('inventory-purchase-orders.cancelOrder')->middleware('permission:inventory.purchase_order.cancel');
});

Route::middleware(['auth:api'])->prefix('/inventory-sales')->group(function () {
    Route::post('/data-table', [SaleController::class, 'dataTable'])->name('inventory-sales.dataTable')->middleware('permission:inventory.sale.view');
    Route::get('/products/{infrastructureId}', [SaleController::class, 'getProducts'])->name('inventory-sales.getProducts');
    Route::post('/save', [SaleController::class, 'save'])->name('inventory-sales.save')->middleware('permission:inventory.sale.create');
    Route::delete('/delete/{id}', [SaleController::class, 'delete'])->name('inventory-sales.delete')->middleware('permission:inventory.sale.delete');
    Route::post('/annul/{id}', [SaleController::class, 'annul'])->name('inventory-sales.annul')->middleware('permission:inventory.sale.annul');
    Route::get('/get-by-id/{id}', [SaleController::class, 'getById'])->name('inventory-sales.getById')->middleware('permission:inventory.sale.view');
});
