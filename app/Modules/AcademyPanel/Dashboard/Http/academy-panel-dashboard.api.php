<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AcademyPanel\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['auth:api'])->prefix('/academy-panel/dashboard')->group(function () {
    Route::get('/summary',              [DashboardController::class, 'summary'])              ->middleware('permission:academy_panel.dashboard.general');
    Route::get('/enrollments-by-group', [DashboardController::class, 'enrollmentsByGroup'])   ->middleware('permission:academy_panel.dashboard.general');
    Route::get('/attendance-overview',  [DashboardController::class, 'attendanceOverview'])   ->middleware('permission:academy_panel.dashboard.general');
    Route::get('/income-by-day',        [DashboardController::class, 'incomeByDay'])          ->middleware('permission:academy_panel.dashboard.general');
    Route::get('/payment-breakdown',    [DashboardController::class, 'paymentBreakdown'])     ->middleware('permission:academy_panel.dashboard.general');
    Route::get('/finance-summary',      [DashboardController::class, 'financeSummary'])       ->middleware('permission:academy_panel.dashboard.finance');
    Route::get('/cash-flow',            [DashboardController::class, 'cashFlow'])             ->middleware('permission:academy_panel.dashboard.finance');
    Route::get('/expenses-by-type',     [DashboardController::class, 'expensesByType'])       ->middleware('permission:academy_panel.dashboard.finance');
    Route::get('/employee-payments',    [DashboardController::class, 'employeePaymentsSummary'])->middleware('permission:academy_panel.dashboard.finance');
    Route::get('/pending-advances',     [DashboardController::class, 'pendingAdvances'])      ->middleware('permission:academy_panel.dashboard.finance');
});