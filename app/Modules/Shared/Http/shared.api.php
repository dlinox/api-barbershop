<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Http\Controllers\InfrastructureController;

Route::middleware(['auth:api'])->prefix('/infrastructures')->group(function () {
    Route::get('/select-items', [InfrastructureController::class, 'selectItems'])->name('infrastructures.selectItems');
});
