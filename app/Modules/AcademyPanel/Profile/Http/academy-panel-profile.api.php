<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AcademyPanel\Profile\Http\Controllers\WorkerController;

Route::middleware(['auth:api'])->prefix('/academy-panel/workers')->group(function () {
    Route::post('/data-table', [WorkerController::class, 'dataTable'])
        ->name('academy-panel.workers.dataTable');

    Route::post('/save', [WorkerController::class, 'save'])
        ->name('academy-panel.workers.save');

    Route::delete('/delete/{id}', [WorkerController::class, 'delete'])
        ->name('academy-panel.workers.delete');

    Route::get('/select-async-items', [WorkerController::class, 'selectAsyncItems'])
        ->name('academy-panel.workers.selectAsyncItems');

    Route::get('/detail/{id}', [WorkerController::class, 'detail'])
        ->name('academy-panel.workers.detail');

    Route::get('/generate-pdf/{id}', [WorkerController::class, 'generatePdf'])
        ->name('academy-panel.workers.generatePdf');
});
