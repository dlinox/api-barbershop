<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BarbershopPanel\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['auth:api'])->prefix('/barbershop-panel/dashboard')->group(function () {
    Route::get('/summary',            [DashboardController::class, 'summary']);
    Route::get('/tickets-by-barber',  [DashboardController::class, 'ticketsByBarber']);
    Route::get('/top-services',       [DashboardController::class, 'topServices']);
    Route::get('/revenue-by-day',     [DashboardController::class, 'revenueByDay']);
    Route::get('/recent-tickets',     [DashboardController::class, 'recentTickets']);
    Route::get('/payment-breakdown',  [DashboardController::class, 'paymentBreakdown']);
});