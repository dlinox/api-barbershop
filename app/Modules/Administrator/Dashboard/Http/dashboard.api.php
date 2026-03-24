<?php

use App\Modules\Administrator\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/dashboard')->group(function () {
    Route::get('/summary', [DashboardController::class, 'summary']);
    Route::get('/revenue-chart', [DashboardController::class, 'revenueChart']);
    Route::get('/tickets-by-barber', [DashboardController::class, 'ticketsByBarber']);
    Route::get('/top-services', [DashboardController::class, 'topServices']);
    Route::get('/enrollments-by-group', [DashboardController::class, 'enrollmentsByGroup']);
    Route::get('/attendance-overview', [DashboardController::class, 'attendanceOverview']);
    Route::get('/cash-flow-chart', [DashboardController::class, 'cashFlowChart']);
    Route::get('/low-stock-alerts', [DashboardController::class, 'lowStockAlerts']);
    Route::get('/recent-tickets', [DashboardController::class, 'recentTickets']);
    Route::get('/reservations-by-status', [DashboardController::class, 'reservationsByStatus']);
    Route::get('/payroll-summary', [DashboardController::class, 'payrollSummary']);
});
