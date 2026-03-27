<?php

use App\Modules\TeacherPanel\Attendance\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/teacher-panel/attendances')->group(function () {
    Route::post('/data-table', [AttendanceController::class, 'dataTable']);
});
