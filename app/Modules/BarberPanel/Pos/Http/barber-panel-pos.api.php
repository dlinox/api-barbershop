<?php

use App\Modules\BarberPanel\Pos\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/barber-panel/pos')->group(function () {
    Route::post('/data-table', [PosController::class, 'dataTable']);
    Route::get('/products/{infrastructureId}', [PosController::class, 'getProducts']);
    Route::post('/save', [PosController::class, 'save']);
    Route::delete('/delete/{id}', [PosController::class, 'delete']);
    Route::post('/annul/{id}', [PosController::class, 'annul']);
    Route::get('/get-by-id/{id}', [PosController::class, 'getById']);
    Route::get('/sales-overview/{cashSessionId}', [PosController::class, 'salesOverview']);
});
