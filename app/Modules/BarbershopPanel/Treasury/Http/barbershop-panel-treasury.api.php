<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\IncomeController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\GeneralExpenseController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\EmployeeAdvanceController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\EmployeePaymentController;
use App\Modules\BarbershopPanel\Treasury\Http\Controllers\WorkerAttendanceController;

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-incomes')->group(function () {
    Route::post('/data-table', [IncomeController::class, 'dataTable'])
        ->name('barbershop-panel.treasury-incomes.dataTable');
    Route::get('/get-by-id/{id}', [IncomeController::class, 'getById'])
        ->name('barbershop-panel.treasury-incomes.getById');
    Route::post('/annul/{id}', [IncomeController::class, 'annul'])
        ->name('barbershop-panel.treasury-incomes.annul');
    Route::get('/generate-pdf/{id}', [IncomeController::class, 'generatePdf'])
        ->name('barbershop-panel.treasury-incomes.generatePdf');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-general-expenses')->group(function () {
    Route::post('/data-table', [GeneralExpenseController::class, 'dataTable'])
        ->name('barbershop-panel.treasury-general-expenses.dataTable');
    Route::post('/save', [GeneralExpenseController::class, 'save'])
        ->name('barbershop-panel.treasury-general-expenses.save');
    Route::get('/get-by-id/{id}', [GeneralExpenseController::class, 'getById'])
        ->name('barbershop-panel.treasury-general-expenses.getById');
    Route::post('/cancel/{id}', [GeneralExpenseController::class, 'cancel'])
        ->name('barbershop-panel.treasury-general-expenses.cancel');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-worker-advances')->group(function () {
    Route::post('/data-table', [EmployeeAdvanceController::class, 'workerDataTable'])
        ->name('barbershop-panel.treasury-worker-advances.dataTable');
    Route::post('/save', [EmployeeAdvanceController::class, 'saveWorkerAdvance'])
        ->name('barbershop-panel.treasury-worker-advances.save');
    Route::delete('/delete/{id}', [EmployeeAdvanceController::class, 'delete'])
        ->name('barbershop-panel.treasury-worker-advances.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-barber-advances')->group(function () {
    Route::post('/data-table', [EmployeeAdvanceController::class, 'barberDataTable'])
        ->name('barbershop-panel.treasury-barber-advances.dataTable');
    Route::post('/save', [EmployeeAdvanceController::class, 'saveBarberAdvance'])
        ->name('barbershop-panel.treasury-barber-advances.save');
    Route::delete('/delete/{id}', [EmployeeAdvanceController::class, 'delete'])
        ->name('barbershop-panel.treasury-barber-advances.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-worker-payments')->group(function () {
    Route::post('/data-table', [EmployeePaymentController::class, 'workerDataTable'])
        ->name('barbershop-panel.treasury-worker-payments.dataTable');
    Route::post('/save', [EmployeePaymentController::class, 'saveWorkerPayment'])
        ->name('barbershop-panel.treasury-worker-payments.save');
    Route::delete('/delete/{id}', [EmployeePaymentController::class, 'delete'])
        ->name('barbershop-panel.treasury-worker-payments.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/treasury-barber-payments')->group(function () {
    Route::post('/data-table', [EmployeePaymentController::class, 'barberDataTable'])
        ->name('barbershop-panel.treasury-barber-payments.dataTable');
    Route::post('/save', [EmployeePaymentController::class, 'saveBarberPayment'])
        ->name('barbershop-panel.treasury-barber-payments.save');
    Route::delete('/delete/{id}', [EmployeePaymentController::class, 'delete'])
        ->name('barbershop-panel.treasury-barber-payments.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/worker-attendances')->group(function () {
    Route::post('/data-table/{date?}', [WorkerAttendanceController::class, 'dataTable'])
        ->name('barbershop-panel.worker-attendances.dataTable');
    Route::post('/register-check-in', [WorkerAttendanceController::class, 'registerCheckIn'])
        ->name('barbershop-panel.worker-attendances.registerCheckIn');
    Route::post('/register-check-out', [WorkerAttendanceController::class, 'registerCheckOut'])
        ->name('barbershop-panel.worker-attendances.registerCheckOut');
    Route::post('/register-absent', [WorkerAttendanceController::class, 'registerAbsent'])
        ->name('barbershop-panel.worker-attendances.registerAbsent');
    Route::post('/update', [WorkerAttendanceController::class, 'update'])
        ->name('barbershop-panel.worker-attendances.update');
});
