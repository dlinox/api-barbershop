<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Http\Controllers\InfrastructureController;
use App\Modules\Shared\Http\Controllers\PersonController;

Route::middleware(['auth:api'])->prefix('/infrastructures')->group(function () {
    Route::get('/select-items', [InfrastructureController::class, 'selectItems'])->name('infrastructures.selectItems');
    Route::get('/items', [InfrastructureController::class, 'items'])->name('infrastructures.items');
});

Route::middleware(['auth:api'])->prefix('/persons')->group(function () {
    Route::get('/select-async-items', [PersonController::class, 'selectAsyncItems'])->name('persons.selectAsyncItems');
});
