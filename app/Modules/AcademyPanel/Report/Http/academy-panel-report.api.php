<?php

use App\Modules\AcademyPanel\Report\Http\Controllers\AcademyPanelReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/academy-panel/report')->group(function () {
    Route::post('/data-table',                    [AcademyPanelReportController::class, 'dataTable'])                   ->name('ap.report.dataTable')                   ->middleware('permission:academy_panel.report.view');
    Route::post('/data-table-attendance-by-group',[AcademyPanelReportController::class, 'dataTableAttendanceByGroup'])  ->name('ap.report.dataTableAttendanceByGroup')  ->middleware('permission:academy_panel.report.view');
    Route::post('/data-table-student-list-by-group',[AcademyPanelReportController::class, 'dataTableStudentListByGroup'])->name('ap.report.dataTableStudentListByGroup')->middleware('permission:academy_panel.report.view');
    Route::post('/generate-income-per-day',       [AcademyPanelReportController::class, 'generateIncomePerDayPdf'])     ->name('ap.report.generateIncomePerDay')        ->middleware('permission:academy_panel.report.view');
    Route::post('/generate-attendance-by-group',  [AcademyPanelReportController::class, 'generateAttendanceByGroupPdf'])->name('ap.report.generateAttendanceByGroup')   ->middleware('permission:academy_panel.report.view');
    Route::post('/generate-student-list-by-group',[AcademyPanelReportController::class, 'generateStudentListByGroupPdf'])->name('ap.report.generateStudentListByGroup') ->middleware('permission:academy_panel.report.view');
    Route::get('/view-pdf/{id}',                  [AcademyPanelReportController::class, 'viewPdf'])                     ->name('ap.report.viewPdf');
});
