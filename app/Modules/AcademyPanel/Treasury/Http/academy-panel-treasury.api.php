<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\IncomeController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\GeneralExpenseController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\EmployeeAdvanceController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\EmployeePaymentController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\WorkerAttendanceController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\CashRegisterController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\CashSessionController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\ExpenseController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\ExpenseTypeController;

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-incomes')->group(function () {
    Route::post('/data-table', [IncomeController::class, 'dataTable'])
        ->name('academy-panel.treasury-incomes.dataTable')
        ->middleware('permission:academy_panel.finance.income');
    Route::get('/get-by-id/{id}', [IncomeController::class, 'getById'])
        ->name('academy-panel.treasury-incomes.getById')
        ->middleware('permission:academy_panel.finance.income');
    Route::post('/annul/{id}', [IncomeController::class, 'annul'])
        ->name('academy-panel.treasury-incomes.annul')
        ->middleware('permission:academy_panel.finance.income');
    Route::get('/generate-pdf/{id}', [IncomeController::class, 'generatePdf'])
        ->name('academy-panel.treasury-incomes.generatePdf')
        ->middleware('permission:academy_panel.finance.income');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-general-expenses')->group(function () {
    Route::post('/data-table', [GeneralExpenseController::class, 'dataTable'])
        ->name('academy-panel.treasury-general-expenses.dataTable')
        ->middleware('permission:academy_panel.finance.general_expense');
    Route::post('/save', [GeneralExpenseController::class, 'save'])
        ->name('academy-panel.treasury-general-expenses.save')
        ->middleware('permission:academy_panel.finance.general_expense');
    Route::get('/get-by-id/{id}', [GeneralExpenseController::class, 'getById'])
        ->name('academy-panel.treasury-general-expenses.getById')
        ->middleware('permission:academy_panel.finance.general_expense');
    Route::post('/cancel/{id}', [GeneralExpenseController::class, 'cancel'])
        ->name('academy-panel.treasury-general-expenses.cancel')
        ->middleware('permission:academy_panel.finance.general_expense');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-worker-advances')->group(function () {
    Route::post('/data-table', [EmployeeAdvanceController::class, 'workerDataTable'])
        ->name('academy-panel.treasury-worker-advances.dataTable')
        ->middleware('permission:academy_panel.finance.worker_advance');
    Route::post('/save', [EmployeeAdvanceController::class, 'saveWorkerAdvance'])
        ->name('academy-panel.treasury-worker-advances.save')
        ->middleware('permission:academy_panel.finance.worker_advance');
    Route::delete('/delete/{id}', [EmployeeAdvanceController::class, 'delete'])
        ->name('academy-panel.treasury-worker-advances.delete')
        ->middleware('permission:academy_panel.finance.worker_advance');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-teacher-advances')->group(function () {
    Route::post('/data-table', [EmployeeAdvanceController::class, 'teacherDataTable'])
        ->name('academy-panel.treasury-teacher-advances.dataTable')
        ->middleware('permission:academy_panel.finance.teacher_advance');
    Route::post('/save', [EmployeeAdvanceController::class, 'saveTeacherAdvance'])
        ->name('academy-panel.treasury-teacher-advances.save')
        ->middleware('permission:academy_panel.finance.teacher_advance');
    Route::delete('/delete/{id}', [EmployeeAdvanceController::class, 'delete'])
        ->name('academy-panel.treasury-teacher-advances.delete')
        ->middleware('permission:academy_panel.finance.teacher_advance');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-worker-payments')->group(function () {
    Route::post('/data-table', [EmployeePaymentController::class, 'workerDataTable'])
        ->name('academy-panel.treasury-worker-payments.dataTable')
        ->middleware('permission:academy_panel.finance.worker_payment');
    Route::post('/payment-summary', [EmployeePaymentController::class, 'workerPaymentSummary'])
        ->name('academy-panel.treasury-worker-payments.paymentSummary')
        ->middleware('permission:academy_panel.finance.worker_payment');
    Route::post('/payment-calculation/{id}', [EmployeePaymentController::class, 'workerPaymentCalculation'])
        ->name('academy-panel.treasury-worker-payments.paymentCalculation')
        ->middleware('permission:academy_panel.finance.worker_payment');
    Route::post('/save', [EmployeePaymentController::class, 'saveWorkerPayment'])
        ->name('academy-panel.treasury-worker-payments.save')
        ->middleware('permission:academy_panel.finance.worker_payment');
    Route::delete('/delete/{id}', [EmployeePaymentController::class, 'delete'])
        ->name('academy-panel.treasury-worker-payments.delete')
        ->middleware('permission:academy_panel.finance.worker_payment');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-teacher-payments')->group(function () {
    Route::post('/data-table', [EmployeePaymentController::class, 'teacherDataTable'])
        ->name('academy-panel.treasury-teacher-payments.dataTable')
        ->middleware('permission:academy_panel.finance.teacher_payment');
    Route::post('/payment-summary', [EmployeePaymentController::class, 'teacherPaymentSummary'])
        ->name('academy-panel.treasury-teacher-payments.paymentSummary')
        ->middleware('permission:academy_panel.finance.teacher_payment');
    Route::post('/payment-calculation/{id}', [EmployeePaymentController::class, 'teacherPaymentCalculation'])
        ->name('academy-panel.treasury-teacher-payments.paymentCalculation')
        ->middleware('permission:academy_panel.finance.teacher_payment');
    Route::post('/save', [EmployeePaymentController::class, 'saveTeacherPayment'])
        ->name('academy-panel.treasury-teacher-payments.save')
        ->middleware('permission:academy_panel.finance.teacher_payment');
    Route::delete('/delete/{id}', [EmployeePaymentController::class, 'delete'])
        ->name('academy-panel.treasury-teacher-payments.delete')
        ->middleware('permission:academy_panel.finance.teacher_payment');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/worker-attendances')->group(function () {
    Route::post('/data-table/{date?}', [WorkerAttendanceController::class, 'dataTable'])
        ->name('academy-panel.worker-attendances.dataTable')
        ->middleware('permission:academy_panel.workers.attendance');
    Route::post('/register-check-in', [WorkerAttendanceController::class, 'registerCheckIn'])
        ->name('academy-panel.worker-attendances.registerCheckIn')
        ->middleware('permission:academy_panel.workers.attendance');
    Route::post('/register-check-out', [WorkerAttendanceController::class, 'registerCheckOut'])
        ->name('academy-panel.worker-attendances.registerCheckOut')
        ->middleware('permission:academy_panel.workers.attendance');
    Route::post('/register-absent', [WorkerAttendanceController::class, 'registerAbsent'])
        ->name('academy-panel.worker-attendances.registerAbsent')
        ->middleware('permission:academy_panel.workers.attendance');
    Route::post('/update', [WorkerAttendanceController::class, 'update'])
        ->name('academy-panel.worker-attendances.update')
        ->middleware('permission:academy_panel.workers.attendance');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-cash-registers')->group(function () {
    Route::get('/select-items/{infrastructureId}', [CashRegisterController::class, 'selectItems'])
        ->name('academy-panel.treasury-cash-registers.selectItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-cash-sessions')->group(function () {
    Route::post('/data-table', [CashSessionController::class, 'dataTable'])
        ->name('academy-panel.treasury-cash-sessions.dataTable')
        ->middleware('permission:academy_panel.reception.cash_session');
    Route::post('/open', [CashSessionController::class, 'openSession'])
        ->name('academy-panel.treasury-cash-sessions.open')
        ->middleware('permission:academy_panel.reception.cash_session');
    Route::post('/close', [CashSessionController::class, 'closeSession'])
        ->name('academy-panel.treasury-cash-sessions.close')
        ->middleware('permission:academy_panel.reception.cash_session');
    Route::get('/closing-pdf/{cashSessionId}', [CashSessionController::class, 'closingPdf'])
        ->name('academy-panel.treasury-cash-sessions.closingPdf')
        ->middleware('permission:academy_panel.reception.cash_session');
    Route::get('/current/{cashRegisterId}', [CashSessionController::class, 'currentSession'])
        ->name('academy-panel.treasury-cash-sessions.current')
        ->middleware('permission:academy_panel.reception.cash_session');
    Route::get('/current-id/{cashRegisterId}', [CashSessionController::class, 'currentSessionId'])
        ->name('academy-panel.treasury-cash-sessions.currentId')
        ->middleware('permission:academy_panel.reception.cash_session');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-expenses')->group(function () {
    Route::get('/list/{cashSessionId}', [ExpenseController::class, 'list'])
        ->name('academy-panel.treasury-expenses.list')
        ->middleware('permission:academy_panel.reception.cash_session');
    Route::post('/save', [ExpenseController::class, 'save'])
        ->name('academy-panel.treasury-expenses.save')
        ->middleware('permission:academy_panel.reception.cash_session');
    Route::delete('/delete/{id}', [ExpenseController::class, 'delete'])
        ->name('academy-panel.treasury-expenses.delete')
        ->middleware('permission:academy_panel.reception.cash_session');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-expense-types')->group(function () {
    Route::get('/select-items', [ExpenseTypeController::class, 'selectItems'])
        ->name('academy-panel.treasury-expense-types.selectItems');
});
