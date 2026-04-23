<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Http\Controllers\InfrastructureController;
use App\Modules\Shared\Http\Controllers\PersonController;
use App\Modules\Shared\Http\Controllers\ProfileController;
use App\Modules\Administrator\Setting\Http\Controllers\PaymentMethodsController;
use App\Modules\Administrator\Setting\Http\Controllers\DocumentTypeController;
use App\Modules\Administrator\Treasury\Http\Controllers\ExpenseTypeController;
use App\Modules\Administrator\Inventory\Http\Controllers\BrandController;
use App\Modules\Administrator\Inventory\Http\Controllers\CategoryController;

Route::middleware(['auth:api'])->prefix('/infrastructures')->group(function () {
    Route::get('/select-items', [InfrastructureController::class, 'selectItems'])->name('infrastructures.selectItems');
    Route::get('/items', [InfrastructureController::class, 'items'])->name('infrastructures.items');
});

Route::middleware(['auth:api'])->prefix('/persons')->group(function () {
    Route::get('/select-async-items', [PersonController::class, 'selectAsyncItems'])->name('persons.selectAsyncItems');
    Route::get('/search-by-document', [PersonController::class, 'searchByDocument'])->name('persons.searchByDocument');
});

Route::middleware(['auth:api'])->prefix('/profile')->group(function () {
    Route::get('/me',               [ProfileController::class, 'me']);
    Route::put('/update-personal',  [ProfileController::class, 'updatePersonal']);
    Route::put('/change-password',  [ProfileController::class, 'changePassword']);
});

// ── Configuración global (sin relación con sede) ──────────────────────────────

Route::middleware(['auth:api'])->prefix('/payment-methods')->group(function () {
    Route::get('/get-active-payment-methods', [PaymentMethodsController::class, 'getActivePaymentMethods'])->name('shared.payment-methods.getActive');
});

Route::middleware(['auth:api'])->prefix('/document-types')->group(function () {
    Route::get('/select-items', [DocumentTypeController::class, 'selectItems'])->name('shared.document-types.selectItems');
});

Route::middleware(['auth:api'])->prefix('/treasury-expense-types')->group(function () {
    Route::get('/select-items', [ExpenseTypeController::class, 'selectItems'])->name('shared.treasury-expense-types.selectItems');
});

Route::middleware(['auth:api'])->prefix('/inventory-brands')->group(function () {
    Route::get('/select-items', [BrandController::class, 'selectItems'])->name('shared.inventory-brands.selectItems');
});

Route::middleware(['auth:api'])->prefix('/inventory-categories')->group(function () {
    Route::get('/select-items', [CategoryController::class, 'selectItems'])->name('shared.inventory-categories.selectItems');
});
