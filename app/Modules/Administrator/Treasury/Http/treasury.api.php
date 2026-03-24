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
    Route::get('/closing-pdf/{cashSessionId}', [CashSessionController::class, 'closingPdf'])->name('treasury-cash-sessions.closingPdf')->middleware('permission:treasury.cash_session.view');
});

Route::middleware(['auth:api'])->prefix('/treasury-expenses')->group(function () {
    Route::get('/list/{cashSessionId}', [ExpenseController::class, 'list'])->name('treasury-expenses.list')->middleware('permission:treasury.expense.view');
    Route::post('/save', [ExpenseController::class, 'save'])->name('treasury-expenses.save')->middleware('permission:treasury.expense.create');
    Route::delete('/delete/{id}', [ExpenseController::class, 'delete'])->name('treasury-expenses.delete')->middleware('permission:treasury.expense.delete');
});

Route::middleware(['auth:api'])->prefix('/treasury-expense-types')->group(function () {
    Route::post('/data-table', [ExpenseTypeController::class, 'dataTable'])->name('treasury-expense-types.dataTable')->middleware('permission:treasury.expense_type.view');
    Route::post('/save', [ExpenseTypeController::class, 'save'])->name('treasury-expense-types.save')->middleware('permission:treasury.expense_type.create,treasury.expense_type.edit');
    Route::get('/select-items', [ExpenseTypeController::class, 'selectItems'])->name('treasury-expense-types.selectItems');
    Route::delete('/delete/{id}', [ExpenseTypeController::class, 'delete'])->name('treasury-expense-types.delete')->middleware('permission:treasury.expense_type.delete');
});

Route::middleware(['auth:api'])->prefix('/treasury-general-expenses')->group(function () {
    Route::post('/data-table', [GeneralExpenseController::class, 'dataTable'])->name('treasury-general-expenses.dataTable')->middleware('permission:treasury.general_expense.view');
    Route::post('/save', [GeneralExpenseController::class, 'save'])->name('treasury-general-expenses.save')->middleware('permission:treasury.general_expense.create,treasury.general_expense.edit');
    Route::get('/get-by-id/{id}', [GeneralExpenseController::class, 'getById'])->name('treasury-general-expenses.getById')->middleware('permission:treasury.general_expense.view');
    Route::post('/cancel/{id}', [GeneralExpenseController::class, 'cancel'])->name('treasury-general-expenses.cancel')->middleware('permission:treasury.general_expense.cancel');
});

Route::middleware(['auth:api'])->prefix('/treasury-incomes')->group(function () {
    Route::post('/data-table', [IncomeController::class, 'dataTable'])->name('treasury-incomes.dataTable')->middleware('permission:treasury.income.view');
    Route::get('/get-by-id/{id}', [IncomeController::class, 'getById'])->name('treasury-incomes.getById')->middleware('permission:treasury.income.view');
    Route::get('/generate-pdf/{id}', [IncomeController::class, 'generatePdf'])->name('treasury-incomes.generatePdf')->middleware('permission:treasury.income.view');
    Route::post('/annul/{id}', [IncomeController::class, 'annul'])->name('treasury-incomes.annul')->middleware('permission:treasury.income.annul');
});

Route::middleware(['auth:api'])->prefix('/treasury-employee-advances')->group(function () {
    Route::post('/data-table', [EmployeeAdvanceController::class, 'dataTable'])->name('treasury-employee-advances.dataTable')->middleware('permission:treasury.employee_advance.view');
    Route::post('/save', [EmployeeAdvanceController::class, 'save'])->name('treasury-employee-advances.save')->middleware('permission:treasury.employee_advance.create,treasury.employee_advance.edit');
    Route::delete('/delete/{id}', [EmployeeAdvanceController::class, 'delete'])->name('treasury-employee-advances.delete')->middleware('permission:treasury.employee_advance.delete');
});

Route::middleware(['auth:api'])->prefix('/treasury-employee-payments')->group(function () {
    Route::post('/data-table', [EmployeePaymentController::class, 'dataTable'])->name('treasury-employee-payments.dataTable')->middleware('permission:treasury.employee_payment.view');
    Route::post('/save', [EmployeePaymentController::class, 'save'])->name('treasury-employee-payments.save')->middleware('permission:treasury.employee_payment.create,treasury.employee_payment.edit');
    Route::delete('/delete/{id}', [EmployeePaymentController::class, 'delete'])->name('treasury-employee-payments.delete')->middleware('permission:treasury.employee_payment.delete');
});

Route::middleware(['auth:api'])->prefix('/workers')->group(function () {
    Route::post('/data-table', [WorkerController::class, 'dataTable'])->name('treasury-workers.dataTable')->middleware('permission:treasury.worker.view');
    Route::post('/payment-summary', [WorkerController::class, 'paymentSummaryDataTable'])->name('treasury-workers.paymentSummary')->middleware('permission:treasury.employee_payment.view');
    Route::post('/payment-calculation/{workerId}', [WorkerController::class, 'paymentCalculation'])->name('treasury-workers.paymentCalculation')->middleware('permission:treasury.employee_payment.view');
    Route::post('/save', [WorkerController::class, 'save'])->name('treasury-workers.save')->middleware('permission:treasury.worker.create,treasury.worker.edit');
    Route::get('/select-async-items', [WorkerController::class, 'selectAsyncItems'])->name('treasury-workers.selectAsyncItems');
    Route::delete('/delete/{id}', [WorkerController::class, 'delete'])->name('treasury-workers.delete')->middleware('permission:treasury.worker.delete');
});

Route::middleware(['auth:api'])->prefix('/worker-attendances')->group(function () {
    Route::post('/data-table/{date?}', [WorkerAttendanceController::class, 'dataTable'])->name('worker-attendances.dataTable')->middleware('permission:treasury.worker_attendance.view');
    Route::post('/register-check-in', [WorkerAttendanceController::class, 'registerCheckIn'])->name('worker-attendances.registerCheckIn')->middleware('permission:treasury.worker_attendance.register');
    Route::post('/register-check-out', [WorkerAttendanceController::class, 'registerCheckOut'])->name('worker-attendances.registerCheckOut')->middleware('permission:treasury.worker_attendance.register');
    Route::post('/register-absent', [WorkerAttendanceController::class, 'registerAbsent'])->name('worker-attendances.registerAbsent')->middleware('permission:treasury.worker_attendance.register');
    Route::post('/update', [WorkerAttendanceController::class, 'update'])->name('worker-attendances.update')->middleware('permission:treasury.worker_attendance.edit');
    Route::post('/generate-qr-code', [WorkerAttendanceController::class, 'generateQrCode'])->name('worker-attendances.generateQrCode')->middleware('permission:treasury.worker_attendance.register');
});
