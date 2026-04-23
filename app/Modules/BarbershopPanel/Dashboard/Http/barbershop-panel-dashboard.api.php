<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BarbershopPanel\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['auth:api'])->prefix('/barbershop-panel/dashboard')->group(function () {
    Route::get('/summary',            [DashboardController::class, 'summary']);
    Route::get('/tickets-by-barber',  [DashboardController::class, 'ticketsByBarber']);
    Route::get('/top-services',       [DashboardController::class, 'topServices']);
    Route::get('/revenue-by-day',     [DashboardController::class, 'revenueByDay']);
    Route::get('/recent-tickets',     [DashboardController::class, 'recentTickets']);
    Route::get('/payment-breakdown',       [DashboardController::class, 'paymentBreakdown']);
    Route::get('/finance-summary',         [DashboardController::class, 'financeSummary']);
    Route::get('/cash-flow',               [DashboardController::class, 'cashFlow']);
    Route::get('/expenses-by-type',        [DashboardController::class, 'expensesByType']);
    Route::get('/employee-payments',       [DashboardController::class, 'employeePaymentsSummary']);
    Route::get('/pending-advances',        [DashboardController::class, 'pendingAdvances']);
});