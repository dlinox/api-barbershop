<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Treasury\Http\Controllers\CashRegisterController;
use App\Modules\Administrator\Treasury\Http\Controllers\CashSessionController;

Route::middleware(['auth:api'])->prefix('/treasury-cash-registers')->group(function () {
    Route::post('/data-table/{infrastructureId}', [CashRegisterController::class, 'dataTable'])->name('treasury-cash-registers.dataTable');
    Route::post('/save', [CashRegisterController::class, 'save'])->name('treasury-cash-registers.save');
    Route::get('/select-items/{infrastructureId}', [CashRegisterController::class, 'selectItems'])->name('treasury-cash-registers.selectItems');
    Route::delete('/delete/{id}', [CashRegisterController::class, 'delete'])->name('treasury-cash-registers.delete');
});

Route::middleware(['auth:api'])->prefix('/treasury-cash-sessions')->group(function () {
    Route::post('/data-table/{cashRegisterId}', [CashSessionController::class, 'dataTable'])->name('treasury-cash-sessions.dataTable');
    Route::post('/open', [CashSessionController::class, 'openSession'])->name('treasury-cash-sessions.open');
    Route::post('/close', [CashSessionController::class, 'closeSession'])->name('treasury-cash-sessions.close');
    Route::get('/current/{cashRegisterId}', [CashSessionController::class, 'currentSession'])->name('treasury-cash-sessions.current');
});
