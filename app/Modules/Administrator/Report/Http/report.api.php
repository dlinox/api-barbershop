<?php

use App\Modules\Administrator\Report\Http\Controllers\ReportAcademyController;
use App\Modules\Administrator\Report\Http\Controllers\ReportBarbershopController;
use App\Modules\Administrator\Report\Http\Controllers\ReportInventoryController;
use App\Modules\Administrator\Report\Http\Controllers\ReportTreasuryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/report-academy')->group(function () {
    Route::post('/data-table', [ReportAcademyController::class, 'dataTable']);
    Route::post('/data-table-attendance-by-group', [ReportAcademyController::class, 'dataTableAttendanceByGroup']);
    Route::post('/data-table-student-list-by-group', [ReportAcademyController::class, 'dataTableStudentListByGroup']);
    Route::get('/summary', [ReportAcademyController::class, 'summary']);
    Route::get('/enrollment-trend', [ReportAcademyController::class, 'enrollmentTrend']);
    Route::get('/revenue-by-type', [ReportAcademyController::class, 'revenueByType']);
    Route::get('/group-occupancy', [ReportAcademyController::class, 'groupOccupancy']);
    Route::get('/group-detail', [ReportAcademyController::class, 'groupDetail']);
    Route::get('/daily-income', [ReportAcademyController::class, 'dailyIncome']);
    Route::post('/generate-income-per-day', [ReportAcademyController::class, 'generateIncomePerDayPdf']);
    Route::post('/generate-attendance-by-group', [ReportAcademyController::class, 'generateAttendanceByGroupPdf']);
    Route::post('/generate-student-list-by-group', [ReportAcademyController::class, 'generateStudentListByGroupPdf']);
    Route::get('/view-pdf/{id}', [ReportAcademyController::class, 'viewPdf']);
});

Route::middleware(['auth:api'])->prefix('/report-barbershop')->group(function () {
    Route::get('/summary', [ReportBarbershopController::class, 'summary']);
    Route::get('/revenue-trend', [ReportBarbershopController::class, 'revenueTrend']);
    Route::get('/top-barbers', [ReportBarbershopController::class, 'topBarbers']);
    Route::get('/services-breakdown', [ReportBarbershopController::class, 'servicesBreakdown']);
    Route::get('/tickets-by-status', [ReportBarbershopController::class, 'ticketsByStatus']);
    Route::get('/hourly-distribution', [ReportBarbershopController::class, 'hourlyDistribution']);
    Route::get('/service-detail', [ReportBarbershopController::class, 'serviceDetail']);
    Route::post('/data-table-income-per-day', [ReportBarbershopController::class, 'dataTableIncomePerDay']);
    Route::post('/data-table-barber-commissions', [ReportBarbershopController::class, 'dataTableBarberCommissions']);
    Route::post('/data-table-cash-session-summary', [ReportBarbershopController::class, 'dataTableCashSessionSummary']);
    Route::post('/generate-income-per-day', [ReportBarbershopController::class, 'generateIncomePerDayPdf']);
    Route::post('/generate-barber-commissions', [ReportBarbershopController::class, 'generateBarberCommissionsPdf']);
    Route::post('/generate-cash-session-summary', [ReportBarbershopController::class, 'generateCashSessionSummaryPdf']);
    Route::get('/view-pdf/{id}', [ReportBarbershopController::class, 'viewPdf']);
});

Route::middleware(['auth:api'])->prefix('/report-treasury')->group(function () {
    // Dashboard
    Route::get('/summary', [ReportTreasuryController::class, 'summary']);
    Route::get('/income-vs-expenses', [ReportTreasuryController::class, 'incomeVsExpenses']);
    Route::get('/payment-methods', [ReportTreasuryController::class, 'paymentMethodsDistribution']);
    Route::get('/expenses-by-type', [ReportTreasuryController::class, 'expensesByType']);
    Route::get('/cash-session-detail', [ReportTreasuryController::class, 'cashSessionDetail']);
    // DataTables
    Route::post('/data-table-income-per-day', [ReportTreasuryController::class, 'dataTableIncomePerDay']);
    Route::post('/data-table-expense-per-day', [ReportTreasuryController::class, 'dataTableExpensePerDay']);
    Route::post('/data-table-cash-session', [ReportTreasuryController::class, 'dataTableCashSession']);
    Route::post('/data-table-income-vs-expense', [ReportTreasuryController::class, 'dataTableIncomeVsExpense']);
    Route::post('/data-table-pending-expenses', [ReportTreasuryController::class, 'dataTablePendingExpenses']);
    // PDF Generation
    Route::post('/generate-income-per-day', [ReportTreasuryController::class, 'generateIncomePerDayPdf']);
    Route::post('/generate-expense-per-day', [ReportTreasuryController::class, 'generateExpensePerDayPdf']);
    Route::post('/generate-cash-session', [ReportTreasuryController::class, 'generateCashSessionPdf']);
    Route::post('/generate-income-vs-expense', [ReportTreasuryController::class, 'generateIncomeVsExpensePdf']);
    Route::post('/generate-pending-expenses', [ReportTreasuryController::class, 'generatePendingExpensesPdf']);
    Route::get('/view-pdf/{id}', [ReportTreasuryController::class, 'viewPdf']);
});

Route::middleware(['auth:api'])->prefix('/report-inventory')->group(function () {
    // Dashboard
    Route::get('/summary', [ReportInventoryController::class, 'summary']);
    Route::get('/sales-trend', [ReportInventoryController::class, 'salesTrend']);
    Route::get('/stock-status', [ReportInventoryController::class, 'stockStatus']);
    Route::get('/top-products', [ReportInventoryController::class, 'topProducts']);
    Route::get('/purchase-order-status', [ReportInventoryController::class, 'purchaseOrderStatus']);
    Route::get('/product-detail', [ReportInventoryController::class, 'productDetail']);
    // DataTables
    Route::post('/data-table-kardex', [ReportInventoryController::class, 'dataTableKardex']);
    Route::post('/data-table-stock-by-product', [ReportInventoryController::class, 'dataTableStockByProduct']);
    Route::post('/data-table-stock-by-infrastructure', [ReportInventoryController::class, 'dataTableStockByInfrastructure']);
    Route::post('/data-table-sales-per-day', [ReportInventoryController::class, 'dataTableSalesPerDay']);
    Route::post('/data-table-low-stock', [ReportInventoryController::class, 'dataTableLowStock']);
    // PDF Generation
    Route::post('/generate-kardex', [ReportInventoryController::class, 'generateKardexPdf']);
    Route::post('/generate-stock-by-product', [ReportInventoryController::class, 'generateStockByProductPdf']);
    Route::post('/generate-stock-by-infrastructure', [ReportInventoryController::class, 'generateStockByInfrastructurePdf']);
    Route::post('/generate-sales-per-day', [ReportInventoryController::class, 'generateSalesPerDayPdf']);
    Route::post('/generate-low-stock', [ReportInventoryController::class, 'generateLowStockPdf']);
    Route::get('/view-pdf/{id}', [ReportInventoryController::class, 'viewPdf']);
});
