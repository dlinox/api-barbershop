<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AcademyPanel\Inventory\Http\Controllers\StockController;
use App\Modules\AcademyPanel\Inventory\Http\Controllers\PurchaseOrderController;
use App\Modules\AcademyPanel\Inventory\Http\Controllers\SupplierController;
use App\Modules\AcademyPanel\Inventory\Http\Controllers\ProductController;
use App\Modules\AcademyPanel\Inventory\Http\Controllers\ProductPresentationController;

Route::middleware(['auth:api'])->prefix('/academy-panel/inventory-stocks')->group(function () {
    Route::post('/data-table', [StockController::class, 'dataTable'])->name('academy-panel.inventory-stocks.dataTable');
    Route::post('/initialize', [StockController::class, 'initializeStock'])->name('academy-panel.inventory-stocks.initialize');
    Route::post('/adjust', [StockController::class, 'adjustStock'])->name('academy-panel.inventory-stocks.adjust');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/inventory-purchase-orders')->group(function () {
    Route::post('/data-table', [PurchaseOrderController::class, 'dataTable'])->name('academy-panel.inventory-purchase-orders.dataTable');
    Route::post('/save', [PurchaseOrderController::class, 'save'])->name('academy-panel.inventory-purchase-orders.save');
    Route::get('/get/{id}', [PurchaseOrderController::class, 'getById'])->name('academy-panel.inventory-purchase-orders.getById');
    Route::post('/receive/{id}', [PurchaseOrderController::class, 'receiveOrder'])->name('academy-panel.inventory-purchase-orders.receive');
    Route::post('/cancel/{id}', [PurchaseOrderController::class, 'cancelOrder'])->name('academy-panel.inventory-purchase-orders.cancel');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/inventory-suppliers')->group(function () {
    Route::post('/data-table', [SupplierController::class, 'dataTable'])->name('academy-panel.inventory-suppliers.dataTable');
    Route::post('/save', [SupplierController::class, 'save'])->name('academy-panel.inventory-suppliers.save');
    Route::delete('/delete/{id}', [SupplierController::class, 'delete'])->name('academy-panel.inventory-suppliers.delete');
    Route::get('/select-items', [SupplierController::class, 'selectItems'])->name('academy-panel.inventory-suppliers.selectItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/inventory-products')->group(function () {
    Route::post('/data-table', [ProductController::class, 'dataTable'])->name('academy-panel.inventory-products.dataTable');
    Route::post('/save', [ProductController::class, 'save'])->name('academy-panel.inventory-products.save');
    Route::delete('/delete/{id}', [ProductController::class, 'delete'])->name('academy-panel.inventory-products.delete');
    Route::get('/select-items', [ProductController::class, 'selectItems'])->name('academy-panel.inventory-products.selectItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/inventory-product-presentations')->group(function () {
    Route::post('/save', [ProductPresentationController::class, 'save'])->name('academy-panel.inventory-product-presentations.save');
    Route::delete('/delete/{id}', [ProductPresentationController::class, 'delete'])->name('academy-panel.inventory-product-presentations.delete');
});
