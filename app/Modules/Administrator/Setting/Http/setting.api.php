<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Setting\Http\Controllers\PaymentMethodsController;
use App\Modules\Administrator\Setting\Http\Controllers\DocumentTypeController;
use App\Modules\Administrator\Setting\Http\Controllers\CompanyController;
use App\Modules\Administrator\Setting\Http\Controllers\CompanyLogoController;
use App\Modules\Administrator\Setting\Http\Controllers\EmployeeScheduleController;
use App\Modules\Administrator\Setting\Http\Controllers\CalendarHolidayController;

Route::get('company/get', [CompanyController::class, 'get'])->name('company.get');
Route::middleware(['auth:api', 'super_admin'])->prefix('/company')->group(function () {
    Route::post('/save', [CompanyController::class, 'save'])->name('company.save');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/company-logo')->group(function () {
    Route::post('/upload', [CompanyLogoController::class, 'upload'])->name('company-logo.upload');
    Route::delete('/delete', [CompanyLogoController::class, 'delete'])->name('company-logo.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/payment-methods')->group(function () {
    Route::post('/data-table', [PaymentMethodsController::class, 'dataTable'])->name('payment-methods.dataTable');
    Route::post('/save', [PaymentMethodsController::class, 'save'])->name('payment-methods.save');
    Route::get('/get-active-payment-methods', [PaymentMethodsController::class, 'getActivePaymentMethods'])->name('payment-methods.getActivePaymentMethods');
    Route::delete('/delete/{id}', [PaymentMethodsController::class, 'delete'])->name('payment-methods.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/document-types')->group(function () {
    Route::post('/data-table', [DocumentTypeController::class, 'dataTable'])->name('document-types.dataTable');
    Route::post('/save', [DocumentTypeController::class, 'save'])->name('document-types.save');
    Route::get('/select-items', [DocumentTypeController::class, 'selectItems'])->name('document-types.selectItems');
    Route::delete('/delete/{code}', [DocumentTypeController::class, 'delete'])->name('document-types.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/employee-schedules')->group(function () {
    Route::get('/get', [EmployeeScheduleController::class, 'get'])->name('employee-schedules.get');
    Route::post('/save', [EmployeeScheduleController::class, 'save'])->name('employee-schedules.save');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/calendar-holidays')->group(function () {
    Route::post('/get-by-month', [CalendarHolidayController::class, 'getByMonth'])->name('calendar-holidays.getByMonth');
    Route::post('/save', [CalendarHolidayController::class, 'save'])->name('calendar-holidays.save');
    Route::delete('/delete/{id}', [CalendarHolidayController::class, 'delete'])->name('calendar-holidays.delete');
});
