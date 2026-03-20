<?php

use Illuminate\Support\Facades\Route;
use App\Common\Http\Controllers\ServerTimeController;
use App\Common\Http\Controllers\DashboardController;

Route::middleware('auth:api')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'API is running',
            'version' => '1.0.0'
        ]);
    });

    Route::get('/server-time', [ServerTimeController::class, 'getServerTime']);

    // Dashboard
    Route::prefix('dashboard')->group(function () {
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
});

// Module routes are now registered in AppServiceProvider
