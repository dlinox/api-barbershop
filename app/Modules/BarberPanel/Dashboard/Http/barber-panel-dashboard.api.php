<?php

use App\Modules\BarberPanel\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/barber-panel/dashboard')->group(function () {
    Route::get('/summary', [DashboardController::class, 'summary']);
    Route::get('/recent-tickets', [DashboardController::class, 'recentTickets']);
    Route::get('/recent-attendance', [DashboardController::class, 'recentAttendance']);
});
