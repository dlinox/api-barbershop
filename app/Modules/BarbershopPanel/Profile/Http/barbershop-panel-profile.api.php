<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BarbershopPanel\Profile\Http\Controllers\WorkerController;

Route::middleware(['auth:api'])->prefix('/barbershop-panel/workers')->group(function () {
    Route::post('/data-table', [WorkerController::class, 'dataTable'])
        ->name('barbershop-panel.workers.dataTable');

    Route::post('/save', [WorkerController::class, 'save'])
        ->name('barbershop-panel.workers.save');

    Route::delete('/delete/{id}', [WorkerController::class, 'delete'])
        ->name('barbershop-panel.workers.delete');

    Route::get('/select-async-items', [WorkerController::class, 'selectAsyncItems'])
        ->name('barbershop-panel.workers.selectAsyncItems');
});
