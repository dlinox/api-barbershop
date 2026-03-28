<?php

use App\Modules\BarberPanel\CashRegister\Http\Controllers\CashRegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/barber-panel/cash-registers')->group(function () {
    Route::get('/items', [CashRegisterController::class, 'items']);
    Route::get('/{cashRegisterId}/session-status', [CashRegisterController::class, 'sessionStatus']);
    Route::post('/open-session', [CashRegisterController::class, 'openSession']);
});
