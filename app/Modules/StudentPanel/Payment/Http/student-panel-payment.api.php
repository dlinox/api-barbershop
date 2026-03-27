<?php

use App\Modules\StudentPanel\Payment\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/student-panel/payments')->group(function () {
    Route::post('/data-table', [PaymentController::class, 'dataTable']);
    Route::get('/generate-pdf/{incomeId}', [PaymentController::class, 'generatePdf']);
});
