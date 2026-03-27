<?php

use App\Modules\TeacherPanel\Group\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/teacher-panel/groups')->group(function () {
    Route::post('/data-table', [GroupController::class, 'dataTable']);
    Route::get('/{groupId}/students', [GroupController::class, 'students']);
    Route::get('/{groupId}/attendance-deadline-status', [GroupController::class, 'attendanceDeadlineStatus']);
    Route::post('/{groupId}/start-attendance-deadline', [GroupController::class, 'startAttendanceDeadline']);
    Route::get('/{groupId}/attendances', [GroupController::class, 'attendances']);
    Route::put('/{groupId}/attendance-status', [GroupController::class, 'updateAttendanceStatus']);
});
