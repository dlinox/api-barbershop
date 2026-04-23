<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Profile\Http\Controllers\WorkerController;

Route::middleware(['auth:api', 'super_admin'])->prefix('/hr/workers')->group(function () {
    Route::post('/data-table', [WorkerController::class, 'dataTable'])
        ->name('hr.workers.dataTable')
        ;

    Route::post('/save', [WorkerController::class, 'save'])
        ->name('hr.workers.save')
        ;

    Route::delete('/delete/{id}', [WorkerController::class, 'delete'])
        ->name('hr.workers.delete')
        ;
});
