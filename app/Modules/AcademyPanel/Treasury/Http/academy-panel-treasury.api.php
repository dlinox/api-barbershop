<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\IncomeController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\GeneralExpenseController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\EmployeeAdvanceController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\EmployeePaymentController;
use App\Modules\AcademyPanel\Treasury\Http\Controllers\WorkerAttendanceController;

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-incomes')->group(function () {
    Route::post('/data-table', [IncomeController::class, 'dataTable'])
        ->name('academy-panel.treasury-incomes.dataTable');
    Route::get('/get-by-id/{id}', [IncomeController::class, 'getById'])
        ->name('academy-panel.treasury-incomes.getById');
    Route::post('/annul/{id}', [IncomeController::class, 'annul'])
        ->name('academy-panel.treasury-incomes.annul');
    Route::get('/generate-pdf/{id}', [IncomeController::class, 'generatePdf'])
        ->name('academy-panel.treasury-incomes.generatePdf');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-general-expenses')->group(function () {
    Route::post('/data-table', [GeneralExpenseController::class, 'dataTable'])
        ->name('academy-panel.treasury-general-expenses.dataTable');
    Route::post('/save', [GeneralExpenseController::class, 'save'])
        ->name('academy-panel.treasury-general-expenses.save');
    Route::get('/get-by-id/{id}', [GeneralExpenseController::class, 'getById'])
        ->name('academy-panel.treasury-general-expenses.getById');
    Route::post('/cancel/{id}', [GeneralExpenseController::class, 'cancel'])
        ->name('academy-panel.treasury-general-expenses.cancel');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-worker-advances')->group(function () {
    Route::post('/data-table', [EmployeeAdvanceController::class, 'workerDataTable'])
        ->name('academy-panel.treasury-worker-advances.dataTable');
    Route::post('/save', [EmployeeAdvanceController::class, 'saveWorkerAdvance'])
        ->name('academy-panel.treasury-worker-advances.save');
    Route::delete('/delete/{id}', [EmployeeAdvanceController::class, 'delete'])
        ->name('academy-panel.treasury-worker-advances.delete');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-teacher-advances')->group(function () {
    Route::post('/data-table', [EmployeeAdvanceController::class, 'teacherDataTable'])
        ->name('academy-panel.treasury-teacher-advances.dataTable');
    Route::post('/save', [EmployeeAdvanceController::class, 'saveTeacherAdvance'])
        ->name('academy-panel.treasury-teacher-advances.save');
    Route::delete('/delete/{id}', [EmployeeAdvanceController::class, 'delete'])
        ->name('academy-panel.treasury-teacher-advances.delete');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-worker-payments')->group(function () {
    Route::post('/data-table', [EmployeePaymentController::class, 'workerDataTable'])
        ->name('academy-panel.treasury-worker-payments.dataTable');
    Route::post('/save', [EmployeePaymentController::class, 'saveWorkerPayment'])
        ->name('academy-panel.treasury-worker-payments.save');
    Route::delete('/delete/{id}', [EmployeePaymentController::class, 'delete'])
        ->name('academy-panel.treasury-worker-payments.delete');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/treasury-teacher-payments')->group(function () {
    Route::post('/data-table', [EmployeePaymentController::class, 'teacherDataTable'])
        ->name('academy-panel.treasury-teacher-payments.dataTable');
    Route::post('/save', [EmployeePaymentController::class, 'saveTeacherPayment'])
        ->name('academy-panel.treasury-teacher-payments.save');
    Route::delete('/delete/{id}', [EmployeePaymentController::class, 'delete'])
        ->name('academy-panel.treasury-teacher-payments.delete');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/worker-attendances')->group(function () {
    Route::post('/data-table/{date?}', [WorkerAttendanceController::class, 'dataTable'])
        ->name('academy-panel.worker-attendances.dataTable');
    Route::post('/register-check-in', [WorkerAttendanceController::class, 'registerCheckIn'])
        ->name('academy-panel.worker-attendances.registerCheckIn');
    Route::post('/register-check-out', [WorkerAttendanceController::class, 'registerCheckOut'])
        ->name('academy-panel.worker-attendances.registerCheckOut');
    Route::post('/register-absent', [WorkerAttendanceController::class, 'registerAbsent'])
        ->name('academy-panel.worker-attendances.registerAbsent');
    Route::post('/update', [WorkerAttendanceController::class, 'update'])
        ->name('academy-panel.worker-attendances.update');
});
