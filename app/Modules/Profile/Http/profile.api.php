<?php

use App\Modules\Profile\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'getProfile']);
    Route::put('/personal-data', [ProfileController::class, 'updatePersonalData']);
    Route::put('/account-data', [ProfileController::class, 'updateAccountData']);
    Route::put('/change-password', [ProfileController::class, 'changePassword']);
});
