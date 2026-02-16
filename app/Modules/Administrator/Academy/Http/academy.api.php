
<?php


use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Academy\Http\Controllers\BranchController;
use App\Modules\Administrator\Academy\Http\Controllers\LevelController;
use App\Modules\Administrator\Academy\Http\Controllers\RoomController;
use App\Modules\Administrator\Academy\Http\Controllers\ScheduleController;
use App\Modules\Administrator\Academy\Http\Controllers\GroupController;
use App\Modules\Administrator\Academy\Http\Controllers\StudentController;
use App\Modules\Administrator\Academy\Http\Controllers\EnrollmentController;
use App\Modules\Administrator\Academy\Http\Controllers\AttendanceController;

Route::middleware(['auth:api'])->prefix('/branches')->group(function () {
    Route::post('/data-table', [BranchController::class, 'dataTable'])->name('branches.dataTable');
    Route::post('/save', [BranchController::class, 'save'])->name('branches.save');
    Route::get('/select-items', [BranchController::class, 'selectItems'])->name('branches.selectItems');
    Route::delete('/delete/{id}', [BranchController::class, 'delete'])->name('branches.delete');
});

Route::middleware(['auth:api'])->prefix('/rooms')->group(function () {
    Route::post('/data-table', [RoomController::class, 'dataTable'])->name('rooms.dataTable');
    Route::post('/save', [RoomController::class, 'save'])->name('rooms.save');
    Route::delete('/delete/{id}', [RoomController::class, 'delete'])->name('rooms.delete');
    Route::get('/select-items', [RoomController::class, 'selectItems'])->name('rooms.selectItems');
});

Route::middleware(['auth:api'])->prefix('/levels')->group(function () {
    Route::post('/data-table', [LevelController::class, 'dataTable'])->name('levels.dataTable');
    Route::post('/save', [LevelController::class, 'save'])->name('levels.save');
    Route::get('/select-items', [LevelController::class, 'selectItems'])->name('levels.selectItems');
    Route::delete('/delete/{id}', [LevelController::class, 'delete'])->name('levels.delete');
});

Route::middleware(['auth:api'])->prefix('/schedules')->group(function () {
    Route::post('/data-table', [ScheduleController::class, 'dataTable'])->name('schedules.dataTable');
    Route::post('/save', [ScheduleController::class, 'save'])->name('schedules.save');
    Route::get('/select-items', [ScheduleController::class, 'selectItems'])->name('schedules.selectItems');
    Route::delete('/delete/{id}', [ScheduleController::class, 'delete'])->name('schedules.delete');
});

Route::middleware(['auth:api'])->prefix('/groups')->group(function () {
    Route::post('/data-table', [GroupController::class, 'dataTable'])->name('groups.dataTable');
    Route::post('/save', [GroupController::class, 'save'])->name('groups.save');
    Route::delete('/delete/{id}', [GroupController::class, 'delete'])->name('groups.delete');
    Route::get('/get-items-by-student/{studentId}', [GroupController::class, 'getItemsByStudent'])->name('groups.getItemsByStudent');
});

Route::middleware(['auth:api'])->prefix('/students')->group(function () {
    Route::post('/data-table', [StudentController::class, 'dataTable'])->name('students.dataTable');
    Route::post('/save', [StudentController::class, 'save'])->name('students.save');
    Route::get('/select-async-items', [StudentController::class, 'selectAsyncItems'])->name('students.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/enrollments')->group(function () {
    Route::post('/data-table', [EnrollmentController::class, 'dataTable'])->name('enrollments.dataTable');
    Route::post('/save', [EnrollmentController::class, 'save'])->name('enrollments.save');
});

Route::middleware(['auth:api'])->prefix('/attendances')->group(function () {
    Route::post('/data-table', [AttendanceController::class, 'dataTable'])->name('attendances.dataTable');
    Route::post('/save', [AttendanceController::class, 'save'])->name('attendances.save');
    Route::get('/get-groups-by-attendance', [AttendanceController::class, 'getGroupsByAttendance'])->name('attendances.getGroupsByAttendance');
    Route::post('/start-attendance-deadline', [AttendanceController::class, 'startAttendanceDeadline'])->name('attendances.startAttendanceDeadline');
    Route::post('/update-attendance-deadline', [AttendanceController::class, 'updateAttendanceDeadline'])->name('attendances.updateAttendanceDeadline');
    Route::post('/register-attendance-by-document', [AttendanceController::class, 'registerAttendanceByDocument'])->name('attendances.registerAttendanceByDocument');
    Route::post('/register-attendance-by-code', [AttendanceController::class, 'registerAttendanceByCode'])->name('attendances.registerAttendanceByCode');
});
