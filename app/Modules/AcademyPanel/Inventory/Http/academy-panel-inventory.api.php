<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AcademyPanel\Inventory\Http\Controllers\StockController;
use App\Modules\AcademyPanel\Inventory\Http\Controllers\PurchaseOrderController;
use App\Modules\AcademyPanel\Inventory\Http\Controllers\SupplierController;
use App\Modules\AcademyPanel\Inventory\Http\Controllers\ProductController;
use App\Modules\AcademyPanel\Inventory\Http\Controllers\ProductPresentationController;
use App\Modules\AcademyPanel\Inventory\Http\Controllers\SaleController;

Route::middleware(['auth:api'])->prefix('/academy-panel/inventory-stocks')->group(function () {
    Route::post('/data-table', [StockController::class, 'dataTable'])      ->name('academy-panel.inventory-stocks.dataTable') ->middleware('permission:academy_panel.inventory.stock');
    Route::post('/initialize', [StockController::class, 'initializeStock'])->name('academy-panel.inventory-stocks.initialize') ->middleware('permission:academy_panel.inventory.stock');
    Route::post('/adjust',     [StockController::class, 'adjustStock'])    ->name('academy-panel.inventory-stocks.adjust')    ->middleware('permission:academy_panel.inventory.stock');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/inventory-purchase-orders')->group(function () {
    Route::post('/data-table',        [PurchaseOrderController::class, 'dataTable'])     ->name('academy-panel.inventory-purchase-orders.dataTable') ->middleware('permission:academy_panel.inventory.purchase_order');
    Route::post('/save',              [PurchaseOrderController::class, 'save'])          ->name('academy-panel.inventory-purchase-orders.save')      ->middleware('permission:academy_panel.inventory.purchase_order');
    Route::get('/get/{id}',           [PurchaseOrderController::class, 'getById'])       ->name('academy-panel.inventory-purchase-orders.getById')   ->middleware('permission:academy_panel.inventory.purchase_order');
    Route::post('/receive/{id}',      [PurchaseOrderController::class, 'receiveOrder'])  ->name('academy-panel.inventory-purchase-orders.receive')   ->middleware('permission:academy_panel.inventory.purchase_order');
    Route::post('/cancel/{id}',       [PurchaseOrderController::class, 'cancelOrder'])   ->name('academy-panel.inventory-purchase-orders.cancel')    ->middleware('permission:academy_panel.inventory.purchase_order');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/inventory-suppliers')->group(function () {
    Route::post('/data-table',    [SupplierController::class, 'dataTable'])  ->name('academy-panel.inventory-suppliers.dataTable') ->middleware('permission:academy_panel.config.supplier');
    Route::post('/save',          [SupplierController::class, 'save'])       ->name('academy-panel.inventory-suppliers.save')      ->middleware('permission:academy_panel.config.supplier');
    Route::delete('/delete/{id}', [SupplierController::class, 'delete'])     ->name('academy-panel.inventory-suppliers.delete')    ->middleware('permission:academy_panel.config.supplier');
    Route::get('/select-items',   [SupplierController::class, 'selectItems'])->name('academy-panel.inventory-suppliers.selectItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/inventory-products')->group(function () {
    Route::post('/data-table',    [ProductController::class, 'dataTable'])  ->name('academy-panel.inventory-products.dataTable') ->middleware('permission:academy_panel.config.product');
    Route::post('/save',          [ProductController::class, 'save'])       ->name('academy-panel.inventory-products.save')      ->middleware('permission:academy_panel.config.product');
    Route::delete('/delete/{id}', [ProductController::class, 'delete'])     ->name('academy-panel.inventory-products.delete')    ->middleware('permission:academy_panel.config.product');
    Route::get('/select-items',   [ProductController::class, 'selectItems'])->name('academy-panel.inventory-products.selectItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/inventory-product-presentations')->group(function () {
    Route::post('/save',          [ProductPresentationController::class, 'save'])  ->name('academy-panel.inventory-product-presentations.save')      ->middleware('permission:academy_panel.config.product');
    Route::delete('/delete/{id}', [ProductPresentationController::class, 'delete'])->name('academy-panel.inventory-product-presentations.delete')    ->middleware('permission:academy_panel.config.product');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/inventory-sales')->group(function () {
    Route::post('/data-table',                       [SaleController::class, 'dataTable'])    ->name('academy-panel.inventory-sales.dataTable')      ->middleware('permission:academy_panel.reception.pos');
    Route::get('/products/{infrastructureId}',       [SaleController::class, 'getProducts'])  ->name('academy-panel.inventory-sales.getProducts')    ->middleware('permission:academy_panel.reception.pos');
    Route::post('/save',                             [SaleController::class, 'save'])         ->name('academy-panel.inventory-sales.save')           ->middleware('permission:academy_panel.reception.pos');
    Route::delete('/delete/{id}',                    [SaleController::class, 'delete'])       ->name('academy-panel.inventory-sales.delete')         ->middleware('permission:academy_panel.reception.pos');
    Route::post('/annul/{id}',                       [SaleController::class, 'annul'])        ->name('academy-panel.inventory-sales.annul')          ->middleware('permission:academy_panel.reception.pos');
    Route::get('/get-by-id/{id}',                    [SaleController::class, 'getById'])      ->name('academy-panel.inventory-sales.getById')        ->middleware('permission:academy_panel.reception.pos');
    Route::get('/sales-overview/{cashSessionId}',    [SaleController::class, 'salesOverview'])->name('academy-panel.inventory-sales.salesOverview')  ->middleware('permission:academy_panel.reception.pos');
});
