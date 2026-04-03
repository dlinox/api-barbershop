<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Setting\Http\Controllers\PaymentMethodsController;
use App\Modules\Administrator\Setting\Http\Controllers\DocumentTypeController;
use App\Modules\Administrator\Setting\Http\Controllers\CompanyController;
use App\Modules\Administrator\Setting\Http\Controllers\CompanyLogoController;
use App\Modules\Administrator\Setting\Http\Controllers\EmployeeScheduleController;

Route::middleware(['auth:api'])->prefix('/company')->group(function () {
    Route::get('/get', [CompanyController::class, 'get'])->name('company.get')->middleware('permission:setting.company.view');
    Route::post('/save', [CompanyController::class, 'save'])->name('company.save')->middleware('permission:setting.company.edit');
});

Route::middleware(['auth:api'])->prefix('/company-logo')->group(function () {
    Route::post('/upload', [CompanyLogoController::class, 'upload'])->name('company-logo.upload')->middleware('permission:setting.company.edit');
    Route::delete('/delete', [CompanyLogoController::class, 'delete'])->name('company-logo.delete')->middleware('permission:setting.company.edit');
});

Route::middleware(['auth:api'])->prefix('/payment-methods')->group(function () {
    Route::post('/data-table', [PaymentMethodsController::class, 'dataTable'])->name('payment-methods.dataTable')->middleware('permission:setting.payment_method.view');
    Route::post('/save', [PaymentMethodsController::class, 'save'])->name('payment-methods.save')->middleware('permission:setting.payment_method.create,setting.payment_method.edit');
    Route::get('/get-active-payment-methods', [PaymentMethodsController::class, 'getActivePaymentMethods'])->name('payment-methods.getActivePaymentMethods');
    Route::delete('/delete/{id}', [PaymentMethodsController::class, 'delete'])->name('payment-methods.delete')->middleware('permission:setting.payment_method.delete');
});

Route::middleware(['auth:api'])->prefix('/document-types')->group(function () {
    Route::post('/data-table', [DocumentTypeController::class, 'dataTable'])->name('document-types.dataTable')->middleware('permission:setting.document_type.view');
    Route::post('/save', [DocumentTypeController::class, 'save'])->name('document-types.save')->middleware('permission:setting.document_type.create,setting.document_type.edit');
    Route::get('/select-items', [DocumentTypeController::class, 'selectItems'])->name('document-types.selectItems');
    Route::delete('/delete/{code}', [DocumentTypeController::class, 'delete'])->name('document-types.delete')->middleware('permission:setting.document_type.delete');
});

Route::middleware(['auth:api'])->prefix('/employee-schedules')->group(function () {
    Route::get('/get', [EmployeeScheduleController::class, 'get'])->name('employee-schedules.get')->middleware('permission:setting.employee_schedule.view');
    Route::post('/save', [EmployeeScheduleController::class, 'save'])->name('employee-schedules.save')->middleware('permission:setting.employee_schedule.edit');
});
