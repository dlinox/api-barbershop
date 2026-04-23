<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AcademyPanel\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['auth:api'])->prefix('/academy-panel/dashboard')->group(function () {
    Route::get('/summary',              [DashboardController::class, 'summary']);
    Route::get('/enrollments-by-group', [DashboardController::class, 'enrollmentsByGroup']);
    Route::get('/attendance-overview',  [DashboardController::class, 'attendanceOverview']);
    Route::get('/income-by-day',        [DashboardController::class, 'incomeByDay']);
    Route::get('/payment-breakdown',    [DashboardController::class, 'paymentBreakdown']);
});