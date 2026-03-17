<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Treasury\Http\Controllers\CashRegisterController;
use App\Modules\Administrator\Treasury\Http\Controllers\CashSessionController;
use App\Modules\Administrator\Treasury\Http\Controllers\ExpenseController;
use App\Modules\Administrator\Treasury\Http\Controllers\IncomeController;

Route::middleware(['auth:api'])->prefix('/treasury-cash-registers')->group(function () {
    Route::post('/data-table/{infrastructureId}', [CashRegisterController::class, 'dataTable'])->name('treasury-cash-registers.dataTable')->middleware('permission:treasury.cash_register.view');
    Route::post('/save', [CashRegisterController::class, 'save'])->name('treasury-cash-registers.save')->middleware('permission:treasury.cash_register.create,treasury.cash_register.edit');
    Route::get('/select-items/{infrastructureId}', [CashRegisterController::class, 'selectItems'])->name('treasury-cash-registers.selectItems');
    Route::delete('/delete/{id}', [CashRegisterController::class, 'delete'])->name('treasury-cash-registers.delete')->middleware('permission:treasury.cash_register.delete');
});

Route::middleware(['auth:api'])->prefix('/treasury-cash-sessions')->group(function () {
    Route::post('/data-table', [CashSessionController::class, 'dataTable'])->name('treasury-cash-sessions.dataTable')->middleware('permission:treasury.cash_session.view');
    Route::post('/open', [CashSessionController::class, 'openSession'])->name('treasury-cash-sessions.open')->middleware('permission:treasury.cash_session.open');
    Route::post('/close', [CashSessionController::class, 'closeSession'])->name('treasury-cash-sessions.close')->middleware('permission:treasury.cash_session.close');
    Route::get('/current/{cashRegisterId}', [CashSessionController::class, 'currentSession'])->name('treasury-cash-sessions.current');
    Route::get('/current-id/{cashRegisterId}', [CashSessionController::class, 'currentSessionId'])->name('treasury-cash-sessions.current-id');
});

Route::middleware(['auth:api'])->prefix('/treasury-expenses')->group(function () {
    Route::get('/list/{cashSessionId}', [ExpenseController::class, 'list'])->name('treasury-expenses.list')->middleware('permission:treasury.expense.view');
    Route::post('/save', [ExpenseController::class, 'save'])->name('treasury-expenses.save')->middleware('permission:treasury.expense.create');
    Route::delete('/delete/{id}', [ExpenseController::class, 'delete'])->name('treasury-expenses.delete')->middleware('permission:treasury.expense.delete');
});

Route::middleware(['auth:api'])->prefix('/treasury-incomes')->group(function () {
    Route::post('/data-table', [IncomeController::class, 'dataTable'])->name('treasury-incomes.dataTable')->middleware('permission:treasury.income.view');
    Route::get('/get-by-id/{id}', [IncomeController::class, 'getById'])->name('treasury-incomes.getById')->middleware('permission:treasury.income.view');
    Route::post('/annul/{id}', [IncomeController::class, 'annul'])->name('treasury-incomes.annul')->middleware('permission:treasury.income.annul');
});
