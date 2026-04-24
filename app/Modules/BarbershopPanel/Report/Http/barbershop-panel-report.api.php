<?php

use App\Modules\BarbershopPanel\Report\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/barbershop-panel/report')->group(function () {

    Route::post('/data-table-income-per-day', [ReportController::class, 'dataTableIncomePerDay'])
        ->name('barbershop-panel.report.income-per-day.dataTable')
        ->middleware('permission:barbershop_panel.report.view');

    Route::post('/data-table-barber-commissions', [ReportController::class, 'dataTableBarberCommissions'])
        ->name('barbershop-panel.report.barber-commissions.dataTable')
        ->middleware('permission:barbershop_panel.report.view');

    Route::post('/data-table-cash-session-summary', [ReportController::class, 'dataTableCashSessionSummary'])
        ->name('barbershop-panel.report.cash-session-summary.dataTable')
        ->middleware('permission:barbershop_panel.report.view');

    Route::get('/select-cash-sessions', [ReportController::class, 'selectCashSessions'])
        ->name('barbershop-panel.report.select-cash-sessions');

    Route::post('/generate-income-per-day', [ReportController::class, 'generateIncomePerDayPdf'])
        ->name('barbershop-panel.report.generate-income-per-day')
        ->middleware('permission:barbershop_panel.report.view');

    Route::post('/generate-barber-commissions', [ReportController::class, 'generateBarberCommissionsPdf'])
        ->name('barbershop-panel.report.generate-barber-commissions')
        ->middleware('permission:barbershop_panel.report.view');

    Route::post('/generate-cash-session-summary', [ReportController::class, 'generateCashSessionSummaryPdf'])
        ->name('barbershop-panel.report.generate-cash-session-summary')
        ->middleware('permission:barbershop_panel.report.view');

    Route::get('/view-pdf/{id}', [ReportController::class, 'viewPdf'])
        ->name('barbershop-panel.report.view-pdf');
});
