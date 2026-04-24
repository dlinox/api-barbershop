<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BarbershopPanel\Inventory\Http\Controllers\StockController;
use App\Modules\BarbershopPanel\Inventory\Http\Controllers\PurchaseOrderController;
use App\Modules\BarbershopPanel\Inventory\Http\Controllers\SupplierController;
use App\Modules\BarbershopPanel\Inventory\Http\Controllers\ProductController;
use App\Modules\BarbershopPanel\Inventory\Http\Controllers\ProductPresentationController;
use App\Modules\BarbershopPanel\Inventory\Http\Controllers\SaleController;

Route::middleware(['auth:api'])->prefix('/barbershop-panel/inventory-stocks')->group(function () {
    Route::post('/data-table', [StockController::class, 'dataTable'])->name('barbershop-panel.inventory-stocks.dataTable')->middleware('permission:barbershop_panel.inventory.stock');
    Route::post('/initialize', [StockController::class, 'initializeStock'])->name('barbershop-panel.inventory-stocks.initialize')->middleware('permission:barbershop_panel.inventory.stock');
    Route::post('/adjust', [StockController::class, 'adjustStock'])->name('barbershop-panel.inventory-stocks.adjust')->middleware('permission:barbershop_panel.inventory.stock');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/inventory-purchase-orders')->group(function () {
    Route::post('/data-table', [PurchaseOrderController::class, 'dataTable'])->name('barbershop-panel.inventory-purchase-orders.dataTable')->middleware('permission:barbershop_panel.inventory.purchase_order');
    Route::post('/save', [PurchaseOrderController::class, 'save'])->name('barbershop-panel.inventory-purchase-orders.save')->middleware('permission:barbershop_panel.inventory.purchase_order');
    Route::get('/get/{id}', [PurchaseOrderController::class, 'getById'])->name('barbershop-panel.inventory-purchase-orders.getById')->middleware('permission:barbershop_panel.inventory.purchase_order');
    Route::post('/receive/{id}', [PurchaseOrderController::class, 'receiveOrder'])->name('barbershop-panel.inventory-purchase-orders.receive')->middleware('permission:barbershop_panel.inventory.purchase_order');
    Route::post('/cancel/{id}', [PurchaseOrderController::class, 'cancelOrder'])->name('barbershop-panel.inventory-purchase-orders.cancel')->middleware('permission:barbershop_panel.inventory.purchase_order');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/inventory-suppliers')->group(function () {
    Route::post('/data-table', [SupplierController::class, 'dataTable'])->name('barbershop-panel.inventory-suppliers.dataTable')->middleware('permission:barbershop_panel.inventory.supplier');
    Route::post('/save', [SupplierController::class, 'save'])->name('barbershop-panel.inventory-suppliers.save')->middleware('permission:barbershop_panel.inventory.supplier');
    Route::delete('/delete/{id}', [SupplierController::class, 'delete'])->name('barbershop-panel.inventory-suppliers.delete')->middleware('permission:barbershop_panel.inventory.supplier');
    Route::get('/select-items', [SupplierController::class, 'selectItems'])->name('barbershop-panel.inventory-suppliers.selectItems');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/inventory-products')->group(function () {
    Route::post('/data-table', [ProductController::class, 'dataTable'])->name('barbershop-panel.inventory-products.dataTable')->middleware('permission:barbershop_panel.inventory.product');
    Route::post('/save', [ProductController::class, 'save'])->name('barbershop-panel.inventory-products.save')->middleware('permission:barbershop_panel.inventory.product');
    Route::delete('/delete/{id}', [ProductController::class, 'delete'])->name('barbershop-panel.inventory-products.delete')->middleware('permission:barbershop_panel.inventory.product');
    Route::get('/select-items', [ProductController::class, 'selectItems'])->name('barbershop-panel.inventory-products.selectItems');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/inventory-product-presentations')->group(function () {
    Route::post('/save', [ProductPresentationController::class, 'save'])->name('barbershop-panel.inventory-product-presentations.save')->middleware('permission:barbershop_panel.inventory.product');
    Route::delete('/delete/{id}', [ProductPresentationController::class, 'delete'])->name('barbershop-panel.inventory-product-presentations.delete')->middleware('permission:barbershop_panel.inventory.product');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/inventory-sales')->group(function () {
    Route::post('/data-table', [SaleController::class, 'dataTable'])->name('barbershop-panel.inventory-sales.dataTable')->middleware('permission:barbershop_panel.reception.pos');
    Route::get('/products/{infrastructureId}', [SaleController::class, 'getProducts'])->name('barbershop-panel.inventory-sales.getProducts')->middleware('permission:barbershop_panel.reception.pos');
    Route::post('/save', [SaleController::class, 'save'])->name('barbershop-panel.inventory-sales.save')->middleware('permission:barbershop_panel.reception.pos');
    Route::delete('/delete/{id}', [SaleController::class, 'delete'])->name('barbershop-panel.inventory-sales.delete')->middleware('permission:barbershop_panel.reception.pos');
    Route::post('/annul/{id}', [SaleController::class, 'annul'])->name('barbershop-panel.inventory-sales.annul')->middleware('permission:barbershop_panel.reception.pos');
    Route::get('/get-by-id/{id}', [SaleController::class, 'getById'])->name('barbershop-panel.inventory-sales.getById')->middleware('permission:barbershop_panel.reception.pos');
    Route::get('/sales-overview/{cashSessionId}', [SaleController::class, 'salesOverview'])->name('barbershop-panel.inventory-sales.salesOverview')->middleware('permission:barbershop_panel.reception.pos');
});
