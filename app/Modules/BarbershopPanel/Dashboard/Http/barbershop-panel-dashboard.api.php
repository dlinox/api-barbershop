<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BarbershopPanel\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['auth:api'])->prefix('/barbershop-panel/dashboard')->group(function () {
    Route::get('/summary',            [DashboardController::class, 'summary'])               ->middleware('permission:barbershop_panel.dashboard.general');
    Route::get('/tickets-by-barber',  [DashboardController::class, 'ticketsByBarber'])        ->middleware('permission:barbershop_panel.dashboard.general');
    Route::get('/top-services',       [DashboardController::class, 'topServices'])            ->middleware('permission:barbershop_panel.dashboard.general');
    Route::get('/recent-tickets',     [DashboardController::class, 'recentTickets'])          ->middleware('permission:barbershop_panel.dashboard.general');
    Route::get('/revenue-by-day',     [DashboardController::class, 'revenueByDay'])           ->middleware('permission:barbershop_panel.dashboard.general');
    Route::get('/payment-breakdown',  [DashboardController::class, 'paymentBreakdown'])       ->middleware('permission:barbershop_panel.dashboard.finance');
    Route::get('/finance-summary',    [DashboardController::class, 'financeSummary'])         ->middleware('permission:barbershop_panel.dashboard.finance');
    Route::get('/cash-flow',          [DashboardController::class, 'cashFlow'])               ->middleware('permission:barbershop_panel.dashboard.finance');
    Route::get('/expenses-by-type',   [DashboardController::class, 'expensesByType'])         ->middleware('permission:barbershop_panel.dashboard.finance');
    Route::get('/employee-payments',  [DashboardController::class, 'employeePaymentsSummary'])->middleware('permission:barbershop_panel.dashboard.finance');
    Route::get('/pending-advances',   [DashboardController::class, 'pendingAdvances'])        ->middleware('permission:barbershop_panel.dashboard.finance');
});