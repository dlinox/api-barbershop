<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Setting\Http\Controllers\PaymentMethodsController;

Route::middleware(['auth:api'])->prefix('/payment-methods')->group(function () {
    Route::post('/data-table', [PaymentMethodsController::class, 'dataTable'])->name('payment-methods.dataTable')->middleware('permission:setting.payment_method.view');
    Route::post('/save', [PaymentMethodsController::class, 'save'])->name('payment-methods.save')->middleware('permission:setting.payment_method.create,setting.payment_method.edit');
    Route::get('/get-active-payment-methods', [PaymentMethodsController::class, 'getActivePaymentMethods'])->name('payment-methods.getActivePaymentMethods');
    Route::delete('/delete/{id}', [PaymentMethodsController::class, 'delete'])->name('payment-methods.delete')->middleware('permission:setting.payment_method.delete');
});
