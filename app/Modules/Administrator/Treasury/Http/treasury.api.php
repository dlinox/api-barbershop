<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Treasury\Http\Controllers\CashRegisterController;
use App\Modules\Administrator\Treasury\Http\Controllers\CashSessionController;
use App\Modules\Administrator\Treasury\Http\Controllers\ExpenseController;
use App\Modules\Administrator\Treasury\Http\Controllers\ExpenseTypeController;
use App\Modules\Administrator\Treasury\Http\Controllers\GeneralExpenseController;
use App\Modules\Administrator\Treasury\Http\Controllers\IncomeController;
use App\Modules\Administrator\Treasury\Http\Controllers\EmployeeAdvanceController;
use App\Modules\Administrator\Treasury\Http\Controllers\EmployeePaymentController;
use App\Modules\Administrator\Treasury\Http\Controllers\WorkerController;
use App\Modules\Administrator\Treasury\Http\Controllers\WorkerAttendanceController;

Route::middleware(['auth:api', 'super_admin'])->prefix('/treasury-cash-registers')->group(function () {
    Route::post('/data-table', [CashRegisterController::class, 'dataTable'])->name('treasury-cash-registers.dataTable');
    Route::post('/save', [CashRegisterController::class, 'save'])->name('treasury-cash-registers.save');
    Route::get('/select-items/{infrastructureId}', [CashRegisterController::class, 'selectItems'])->name('treasury-cash-registers.selectItems');
    Route::delete('/delete/{id}', [CashRegisterController::class, 'delete'])->name('treasury-cash-registers.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/treasury-cash-sessions')->group(function () {
    Route::post('/data-table', [CashSessionController::class, 'dataTable'])->name('treasury-cash-sessions.dataTable');
    Route::post('/open', [CashSessionController::class, 'openSession'])->name('treasury-cash-sessions.open');
    Route::post('/close', [CashSessionController::class, 'closeSession'])->name('treasury-cash-sessions.close');
    Route::get('/current/{cashRegisterId}', [CashSessionController::class, 'currentSession'])->name('treasury-cash-sessions.current');
    Route::get('/current-id/{cashRegisterId}', [CashSessionController::class, 'currentSessionId'])->name('treasury-cash-sessions.current-id');
    Route::get('/closing-pdf/{cashSessionId}', [CashSessionController::class, 'closingPdf'])->name('treasury-cash-sessions.closingPdf');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/treasury-expenses')->group(function () {
    Route::get('/list/{cashSessionId}', [ExpenseController::class, 'list'])->name('treasury-expenses.list');
    Route::post('/save', [ExpenseController::class, 'save'])->name('treasury-expenses.save');
    Route::delete('/delete/{id}', [ExpenseController::class, 'delete'])->name('treasury-expenses.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/treasury-expense-types')->group(function () {
    Route::post('/data-table', [ExpenseTypeController::class, 'dataTable'])->name('treasury-expense-types.dataTable');
    Route::post('/save', [ExpenseTypeController::class, 'save'])->name('treasury-expense-types.save');
    Route::get('/select-items', [ExpenseTypeController::class, 'selectItems'])->name('treasury-expense-types.selectItems');
    Route::delete('/delete/{id}', [ExpenseTypeController::class, 'delete'])->name('treasury-expense-types.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/treasury-general-expenses')->group(function () {
    Route::post('/data-table', [GeneralExpenseController::class, 'dataTable'])->name('treasury-general-expenses.dataTable');
    Route::post('/save', [GeneralExpenseController::class, 'save'])->name('treasury-general-expenses.save');
    Route::get('/get-by-id/{id}', [GeneralExpenseController::class, 'getById'])->name('treasury-general-expenses.getById');
    Route::post('/cancel/{id}', [GeneralExpenseController::class, 'cancel'])->name('treasury-general-expenses.cancel');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/treasury-incomes')->group(function () {
    Route::post('/data-table', [IncomeController::class, 'dataTable'])->name('treasury-incomes.dataTable');
    Route::get('/get-by-id/{id}', [IncomeController::class, 'getById'])->name('treasury-incomes.getById');
    Route::get('/generate-pdf/{id}', [IncomeController::class, 'generatePdf'])->name('treasury-incomes.generatePdf');
    Route::post('/annul/{id}', [IncomeController::class, 'annul'])->name('treasury-incomes.annul');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/treasury-employee-advances')->group(function () {
    Route::post('/data-table', [EmployeeAdvanceController::class, 'dataTable'])->name('treasury-employee-advances.dataTable');
    Route::post('/save', [EmployeeAdvanceController::class, 'save'])->name('treasury-employee-advances.save');
    Route::delete('/delete/{id}', [EmployeeAdvanceController::class, 'delete'])->name('treasury-employee-advances.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/treasury-employee-payments')->group(function () {
    Route::post('/data-table', [EmployeePaymentController::class, 'dataTable'])->name('treasury-employee-payments.dataTable');
    Route::post('/save', [EmployeePaymentController::class, 'save'])->name('treasury-employee-payments.save');
    Route::delete('/delete/{id}', [EmployeePaymentController::class, 'delete'])->name('treasury-employee-payments.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/workers')->group(function () {
    Route::post('/data-table', [WorkerController::class, 'dataTable'])->name('treasury-workers.dataTable');
    Route::post('/payment-summary', [WorkerController::class, 'paymentSummaryDataTable'])->name('treasury-workers.paymentSummary');
    Route::post('/payment-calculation/{workerId}', [WorkerController::class, 'paymentCalculation'])->name('treasury-workers.paymentCalculation');
    Route::post('/save', [WorkerController::class, 'save'])->name('treasury-workers.save');
    Route::get('/select-async-items', [WorkerController::class, 'selectAsyncItems'])->name('treasury-workers.selectAsyncItems');
    Route::delete('/delete/{id}', [WorkerController::class, 'delete'])->name('treasury-workers.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/worker-attendances')->group(function () {
    Route::post('/data-table/{date?}', [WorkerAttendanceController::class, 'dataTable'])->name('worker-attendances.dataTable');
    Route::post('/register-check-in', [WorkerAttendanceController::class, 'registerCheckIn'])->name('worker-attendances.registerCheckIn');
    Route::post('/register-check-out', [WorkerAttendanceController::class, 'registerCheckOut'])->name('worker-attendances.registerCheckOut');
    Route::post('/register-absent', [WorkerAttendanceController::class, 'registerAbsent'])->name('worker-attendances.registerAbsent');
    Route::post('/update', [WorkerAttendanceController::class, 'update'])->name('worker-attendances.update');
    Route::post('/generate-qr-code', [WorkerAttendanceController::class, 'generateQrCode'])->name('worker-attendances.generateQrCode');
});
