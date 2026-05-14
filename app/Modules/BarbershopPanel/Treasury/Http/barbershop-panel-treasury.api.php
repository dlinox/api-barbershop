<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\IncomeController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\GeneralExpenseController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\EmployeeAdvanceController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\EmployeePaymentController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\WorkerAttendanceController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\CashRegisterController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\CashSessionController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\ExpenseController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\ExpenseTypeController;

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-incomes')->group(function () {
    Route::post('/data-table', [IncomeController::class, 'dataTable'])
        ->name('barbershop-panel.treasury-incomes.dataTable')
        ->middleware('permission:barbershop_panel.finance.income');
    Route::get('/get-by-id/{id}', [IncomeController::class, 'getById'])
        ->name('barbershop-panel.treasury-incomes.getById')
        ->middleware('permission:barbershop_panel.finance.income');
    Route::post('/annul/{id}', [IncomeController::class, 'annul'])
        ->name('barbershop-panel.treasury-incomes.annul')
        ->middleware('permission:barbershop_panel.finance.income');
    Route::get('/generate-pdf/{id}', [IncomeController::class, 'generatePdf'])
        ->name('barbershop-panel.treasury-incomes.generatePdf');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-general-expenses')->group(function () {
    Route::post('/data-table', [GeneralExpenseController::class, 'dataTable'])
        ->name('barbershop-panel.treasury-general-expenses.dataTable')
        ->middleware('permission:barbershop_panel.finance.general_expense');
    Route::post('/save', [GeneralExpenseController::class, 'save'])
        ->name('barbershop-panel.treasury-general-expenses.save')
        ->middleware('permission:barbershop_panel.finance.general_expense');
    Route::get('/get-by-id/{id}', [GeneralExpenseController::class, 'getById'])
        ->name('barbershop-panel.treasury-general-expenses.getById')
        ->middleware('permission:barbershop_panel.finance.general_expense');
    Route::post('/cancel/{id}', [GeneralExpenseController::class, 'cancel'])
        ->name('barbershop-panel.treasury-general-expenses.cancel')
        ->middleware('permission:barbershop_panel.finance.general_expense');
    Route::post('/approve/{id}', [GeneralExpenseController::class, 'approve'])
        ->name('barbershop-panel.treasury-general-expenses.approve')
        ->middleware('permission:barbershop_panel.finance.general_expense');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-worker-advances')->group(function () {
    Route::post('/data-table', [EmployeeAdvanceController::class, 'workerDataTable'])
        ->name('barbershop-panel.treasury-worker-advances.dataTable')
        ->middleware('permission:barbershop_panel.finance.worker_advance');
    Route::post('/save', [EmployeeAdvanceController::class, 'saveWorkerAdvance'])
        ->name('barbershop-panel.treasury-worker-advances.save')
        ->middleware('permission:barbershop_panel.finance.worker_advance');
    Route::delete('/delete/{id}', [EmployeeAdvanceController::class, 'delete'])
        ->name('barbershop-panel.treasury-worker-advances.delete')
        ->middleware('permission:barbershop_panel.finance.worker_advance');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-barber-advances')->group(function () {
    Route::post('/data-table', [EmployeeAdvanceController::class, 'barberDataTable'])
        ->name('barbershop-panel.treasury-barber-advances.dataTable')
        ->middleware('permission:barbershop_panel.finance.barber_advance');
    Route::post('/save', [EmployeeAdvanceController::class, 'saveBarberAdvance'])
        ->name('barbershop-panel.treasury-barber-advances.save')
        ->middleware('permission:barbershop_panel.finance.barber_advance');
    Route::delete('/delete/{id}', [EmployeeAdvanceController::class, 'delete'])
        ->name('barbershop-panel.treasury-barber-advances.delete')
        ->middleware('permission:barbershop_panel.finance.barber_advance');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-worker-payments')->group(function () {
    Route::post('/data-table', [EmployeePaymentController::class, 'workerDataTable'])
        ->name('barbershop-panel.treasury-worker-payments.dataTable')
        ->middleware('permission:barbershop_panel.finance.worker_payment');
    Route::post('/payment-summary', [EmployeePaymentController::class, 'workerPaymentSummary'])
        ->name('barbershop-panel.treasury-worker-payments.paymentSummary')
        ->middleware('permission:barbershop_panel.finance.worker_payment');
    Route::post('/payment-calculation/{workerId}', [EmployeePaymentController::class, 'workerPaymentCalculation'])
        ->name('barbershop-panel.treasury-worker-payments.paymentCalculation')
        ->middleware('permission:barbershop_panel.finance.worker_payment');
    Route::post('/save', [EmployeePaymentController::class, 'saveWorkerPayment'])
        ->name('barbershop-panel.treasury-worker-payments.save')
        ->middleware('permission:barbershop_panel.finance.worker_payment');
    Route::delete('/delete/{id}', [EmployeePaymentController::class, 'delete'])
        ->name('barbershop-panel.treasury-worker-payments.delete')
        ->middleware('permission:barbershop_panel.finance.worker_payment');
    Route::get('/generate-pdf/{id}', [EmployeePaymentController::class, 'generateWorkerPaymentPdf'])
        ->name('barbershop-panel.treasury-worker-payments.generatePdf')
        ->middleware('permission:barbershop_panel.finance.worker_payment');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-barber-payments')->group(function () {
    Route::post('/data-table', [EmployeePaymentController::class, 'barberDataTable'])
        ->name('barbershop-panel.treasury-barber-payments.dataTable')
        ->middleware('permission:barbershop_panel.finance.barber_payment');
    Route::post('/payment-summary', [EmployeePaymentController::class, 'barberPaymentSummary'])
        ->name('barbershop-panel.treasury-barber-payments.paymentSummary')
        ->middleware('permission:barbershop_panel.finance.barber_payment');
    Route::post('/payment-calculation/{barberId}', [EmployeePaymentController::class, 'barberPaymentCalculation'])
        ->name('barbershop-panel.treasury-barber-payments.paymentCalculation')
        ->middleware('permission:barbershop_panel.finance.barber_payment');
    Route::post('/save', [EmployeePaymentController::class, 'saveBarberPayment'])
        ->name('barbershop-panel.treasury-barber-payments.save')
        ->middleware('permission:barbershop_panel.finance.barber_payment');
    Route::delete('/delete/{id}', [EmployeePaymentController::class, 'delete'])
        ->name('barbershop-panel.treasury-barber-payments.delete')
        ->middleware('permission:barbershop_panel.finance.barber_payment');
    Route::get('/generate-pdf/{id}', [EmployeePaymentController::class, 'generateBarberPaymentPdf'])
        ->name('barbershop-panel.treasury-barber-payments.generatePdf')
        ->middleware('permission:barbershop_panel.finance.barber_payment');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/worker-attendances')->group(function () {
    Route::post('/data-table/{date?}', [WorkerAttendanceController::class, 'dataTable'])
        ->name('barbershop-panel.worker-attendances.dataTable')
        ->middleware('permission:barbershop_panel.attendance.worker');
    Route::post('/register-check-in', [WorkerAttendanceController::class, 'registerCheckIn'])
        ->name('barbershop-panel.worker-attendances.registerCheckIn')
        ->middleware('permission:barbershop_panel.attendance.worker');
    Route::post('/register-check-out', [WorkerAttendanceController::class, 'registerCheckOut'])
        ->name('barbershop-panel.worker-attendances.registerCheckOut')
        ->middleware('permission:barbershop_panel.attendance.worker');
    Route::post('/register-absent', [WorkerAttendanceController::class, 'registerAbsent'])
        ->name('barbershop-panel.worker-attendances.registerAbsent')
        ->middleware('permission:barbershop_panel.attendance.worker');
    Route::post('/update', [WorkerAttendanceController::class, 'update'])
        ->name('barbershop-panel.worker-attendances.update')
        ->middleware('permission:barbershop_panel.attendance.worker');
    Route::get('/history/{workerId}', [WorkerAttendanceController::class, 'history'])
        ->name('barbershop-panel.worker-attendances.history')
        ->middleware('permission:barbershop_panel.attendance.worker');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-cash-registers')->group(function () {
    Route::get('/select-items/{infrastructureId}', [CashRegisterController::class, 'selectItems'])
        ->name('barbershop-panel.treasury-cash-registers.selectItems');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-cash-sessions')->group(function () {
    Route::post('/data-table', [CashSessionController::class, 'dataTable'])
        ->name('barbershop-panel.treasury-cash-sessions.dataTable')
        ->middleware('permission:barbershop_panel.reception.cash_session');
    Route::post('/open', [CashSessionController::class, 'openSession'])
        ->name('barbershop-panel.treasury-cash-sessions.open')
        ->middleware('permission:barbershop_panel.reception.cash_session');
    Route::post('/close', [CashSessionController::class, 'closeSession'])
        ->name('barbershop-panel.treasury-cash-sessions.close')
        ->middleware('permission:barbershop_panel.reception.cash_session');
    Route::get('/closing-pdf/{cashSessionId}', [CashSessionController::class, 'closingPdf'])
        ->name('barbershop-panel.treasury-cash-sessions.closingPdf')
        ->middleware('permission:barbershop_panel.reception.cash_session');
    Route::get('/current/{cashRegisterId}', [CashSessionController::class, 'currentSession'])
        ->name('barbershop-panel.treasury-cash-sessions.current')
        ->middleware('permission:barbershop_panel.reception.cash_session');
    Route::get('/current-id/{cashRegisterId}', [CashSessionController::class, 'currentSessionId'])
        ->name('barbershop-panel.treasury-cash-sessions.currentId')
        ->middleware('permission:barbershop_panel.reception.cash_session');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-expenses')->group(function () {
    Route::get('/list/{cashSessionId}', [ExpenseController::class, 'list'])
        ->name('barbershop-panel.treasury-expenses.list')
        ->middleware('permission:barbershop_panel.reception.cash_session');
    Route::post('/save', [ExpenseController::class, 'save'])
        ->name('barbershop-panel.treasury-expenses.save')
        ->middleware('permission:barbershop_panel.reception.cash_session');
    Route::delete('/delete/{id}', [ExpenseController::class, 'delete'])
        ->name('barbershop-panel.treasury-expenses.delete')
        ->middleware('permission:barbershop_panel.reception.cash_session');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-expense-types')->group(function () {
    Route::get('/select-items', [ExpenseTypeController::class, 'selectItems'])
        ->name('barbershop-panel.treasury-expense-types.selectItems');
});
