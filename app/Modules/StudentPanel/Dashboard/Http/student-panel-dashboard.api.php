<?php

use App\Modules\StudentPanel\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/student-panel/dashboard')->group(function () {
    Route::get('/summary', [DashboardController::class, 'summary']);
    Route::get('/upcoming-classes', [DashboardController::class, 'upcomingClasses']);
    Route::get('/recent-attendance', [DashboardController::class, 'recentAttendance']);
});
