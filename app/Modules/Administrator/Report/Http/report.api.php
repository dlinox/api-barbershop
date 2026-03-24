<?php

use App\Modules\Administrator\Report\Http\Controllers\ReportAcademyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/report-academy')->group(function () {
    Route::get('/summary', [ReportAcademyController::class, 'summary']);
    Route::get('/enrollment-trend', [ReportAcademyController::class, 'enrollmentTrend']);
    Route::get('/revenue-by-type', [ReportAcademyController::class, 'revenueByType']);
    Route::get('/group-occupancy', [ReportAcademyController::class, 'groupOccupancy']);
    Route::get('/group-detail', [ReportAcademyController::class, 'groupDetail']);
    Route::get('/daily-income', [ReportAcademyController::class, 'dailyIncome']);
});
