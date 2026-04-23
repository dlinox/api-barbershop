<?php

use App\Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('auth/sign-in', [AuthController::class, 'signIn']);
Route::get('auth/google', [AuthController::class, 'googleRedirect']);
Route::get('auth/google/callback', [AuthController::class, 'googleCallback']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::get('auth/profiles', [AuthController::class, 'profiles']);
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/select-profile/{profileId}', [AuthController::class, 'selectProfile']);
    Route::get('auth/admin-infrastructures', [AuthController::class, 'adminInfrastructures']);
    Route::post('auth/select-infrastructure/{infrastructureId}', [AuthController::class, 'selectInfrastructure']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::post('auth/sign-out', [AuthController::class, 'signOut']);
});
