<?php

use App\Modules\StudentPanel\Attendance\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/student-panel/attendances')->group(function () {
    Route::post('/data-table', [AttendanceController::class, 'dataTable']);
});
