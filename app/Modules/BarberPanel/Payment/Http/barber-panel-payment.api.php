<?php

use App\Modules\BarberPanel\Payment\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/barber-panel/payments')->group(function () {
    Route::post('/data-table', [PaymentController::class, 'dataTable']);
});
