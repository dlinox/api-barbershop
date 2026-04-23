<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AcademyPanel\Academy\Http\Controllers\BranchController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\RoomController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\LevelController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\ScheduleController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\GroupController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\StudentController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\TeacherController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\EnrollmentController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\AttendanceController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\EnrollmentPaymentController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\MaterialController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\TeacherAttendanceController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\StudentGuardianController;
use App\Modules\AcademyPanel\Academy\Http\Controllers\EnrollmentPaymentAdvanceController;

Route::middleware(['auth:api'])->prefix('/academy-panel/branches')->group(function () {
    Route::post('/data-table',    [BranchController::class, 'dataTable'])  ->name('ap.branches.dataTable');
    Route::post('/save',          [BranchController::class, 'save'])       ->name('ap.branches.save');
    Route::get('/select-items',   [BranchController::class, 'selectItems'])->name('ap.branches.selectItems');
    Route::delete('/delete/{id}', [BranchController::class, 'delete'])     ->name('ap.branches.delete');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/rooms')->group(function () {
    Route::post('/data-table',    [RoomController::class, 'dataTable'])  ->name('ap.rooms.dataTable');
    Route::post('/save',          [RoomController::class, 'save'])       ->name('ap.rooms.save');
    Route::delete('/delete/{id}', [RoomController::class, 'delete'])     ->name('ap.rooms.delete');
    Route::get('/select-items',   [RoomController::class, 'selectItems'])->name('ap.rooms.selectItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/levels')->group(function () {
    Route::post('/data-table',    [LevelController::class, 'dataTable'])  ->name('ap.levels.dataTable');
    Route::post('/save',          [LevelController::class, 'save'])       ->name('ap.levels.save');
    Route::get('/select-items',   [LevelController::class, 'selectItems'])->name('ap.levels.selectItems');
    Route::delete('/delete/{id}', [LevelController::class, 'delete'])     ->name('ap.levels.delete');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/schedules')->group(function () {
    Route::post('/data-table',    [ScheduleController::class, 'dataTable'])  ->name('ap.schedules.dataTable');
    Route::post('/save',          [ScheduleController::class, 'save'])       ->name('ap.schedules.save');
    Route::get('/select-items',   [ScheduleController::class, 'selectItems'])->name('ap.schedules.selectItems');
    Route::delete('/delete/{id}', [ScheduleController::class, 'delete'])     ->name('ap.schedules.delete');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/groups')->group(function () {
    Route::post('/data-table',                               [GroupController::class, 'dataTable'])                   ->name('ap.groups.dataTable');
    Route::post('/save',                                     [GroupController::class, 'save'])                        ->name('ap.groups.save');
    Route::delete('/delete/{id}',                            [GroupController::class, 'delete'])                      ->name('ap.groups.delete');
    Route::post('/assign-teacher',                           [GroupController::class, 'assignTeacher'])               ->name('ap.groups.assignTeacher');
    Route::get('/check-teacher/{groupId}/{teacherId}',       [GroupController::class, 'checkTeacher'])                ->name('ap.groups.checkTeacher');
    Route::get('/select-items',                              [GroupController::class, 'selectItems'])                 ->name('ap.groups.selectItems');
    Route::get('/enrollment/get-availables/{studentId}',     [GroupController::class, 'getAvailableEnrollmentGroups'])->name('ap.groups.getAvailableEnrollmentGroups');
    Route::get('/get-active-and-upcoming',                   [GroupController::class, 'getActiveAndUpcoming'])        ->name('ap.groups.getActiveAndUpcoming');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/students')->group(function () {
    Route::post('/data-table',        [StudentController::class, 'dataTable'])       ->name('ap.students.dataTable');
    Route::post('/save',              [StudentController::class, 'save'])            ->name('ap.students.save');
    Route::get('/select-async-items', [StudentController::class, 'selectAsyncItems'])->name('ap.students.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/teachers')->group(function () {
    Route::post('/data-table',        [TeacherController::class, 'dataTable'])       ->name('ap.teachers.dataTable');
    Route::post('/save',              [TeacherController::class, 'save'])            ->name('ap.teachers.save');
    Route::get('/select-async-items', [TeacherController::class, 'selectAsyncItems'])->name('ap.teachers.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/enrollments')->group(function () {
    Route::post('/data-table',           [EnrollmentController::class, 'dataTable'])          ->name('ap.enrollments.dataTable');
    Route::post('/save',                 [EnrollmentController::class, 'save'])               ->name('ap.enrollments.save');
    Route::post('/save-without-payment', [EnrollmentController::class, 'saveWithoutPayment']) ->name('ap.enrollments.saveWithoutPayment');
    Route::post('/update',               [EnrollmentController::class, 'update'])             ->name('ap.enrollments.update');
    Route::get('/get/{id}',              [EnrollmentController::class, 'getEnrollment'])      ->name('ap.enrollments.getEnrollment');
    Route::post('/register-payment',     [EnrollmentController::class, 'registerPayment'])    ->name('ap.enrollments.registerPayment');
    Route::get('/generate-pdf/{id}',     [EnrollmentController::class, 'generatePdf'])        ->name('ap.enrollments.generatePdf');
    Route::get('/detail/{id}',           [EnrollmentController::class, 'detail'])             ->name('ap.enrollments.detail');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/attendances')->group(function () {
    Route::post('/data-table',                      [AttendanceController::class, 'dataTable'])                   ->name('ap.attendances.dataTable');
    Route::get('/get-groups-by-attendance',          [AttendanceController::class, 'getGroupsByAttendance'])       ->name('ap.attendances.getGroupsByAttendance');
    Route::post('/start-attendance-deadline',        [AttendanceController::class, 'startAttendanceDeadline'])     ->name('ap.attendances.startAttendanceDeadline');
    Route::post('/update-attendance-deadline',       [AttendanceController::class, 'updateAttendanceDeadline'])    ->name('ap.attendances.updateAttendanceDeadline');
    Route::post('/register-attendance-by-document',  [AttendanceController::class, 'registerAttendanceByDocument'])->name('ap.attendances.registerAttendanceByDocument');
    Route::post('/register-attendance-by-code',      [AttendanceController::class, 'registerAttendanceByCode'])    ->name('ap.attendances.registerAttendanceByCode');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/enrollment-payments')->group(function () {
    Route::post('/data-table',                             [EnrollmentPaymentController::class, 'dataTable'])             ->name('ap.enrollment-payments.dataTable');
    Route::get('/history-by-enrollment-id/{enrollmentId}', [EnrollmentPaymentController::class, 'historyByEnrollmentId'])->name('ap.enrollment-payments.historyByEnrollmentId');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/materials')->group(function () {
    Route::post('/data-table',    [MaterialController::class, 'dataTable'])  ->name('ap.materials.dataTable');
    Route::post('/save',          [MaterialController::class, 'save'])       ->name('ap.materials.save');
    Route::get('/select-items',   [MaterialController::class, 'selectItems'])->name('ap.materials.selectItems');
    Route::delete('/delete/{id}', [MaterialController::class, 'delete'])     ->name('ap.materials.delete');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/teacher-attendances')->group(function () {
    Route::post('/data-table/{date?}', [TeacherAttendanceController::class, 'dataTable'])       ->name('ap.teacher-attendances.dataTable');
    Route::post('/register-check-in',  [TeacherAttendanceController::class, 'registerCheckIn']) ->name('ap.teacher-attendances.registerCheckIn');
    Route::post('/register-check-out', [TeacherAttendanceController::class, 'registerCheckOut'])->name('ap.teacher-attendances.registerCheckOut');
    Route::post('/register-absent',    [TeacherAttendanceController::class, 'registerAbsent'])  ->name('ap.teacher-attendances.registerAbsent');
    Route::post('/update',             [TeacherAttendanceController::class, 'update'])           ->name('ap.teacher-attendances.update');
    Route::post('/generate-qr-code',   [TeacherAttendanceController::class, 'generateQrCode'])  ->name('ap.teacher-attendances.generateQrCode');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/student-guardians')->group(function () {
    Route::get('/by-student/{studentId}', [StudentGuardianController::class, 'findByStudentId'])->name('ap.student-guardians.byStudent');
    Route::post('/save',                  [StudentGuardianController::class, 'save'])           ->name('ap.student-guardians.save');
    Route::delete('/delete/{id}',         [StudentGuardianController::class, 'delete'])         ->name('ap.student-guardians.delete');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/enrollment-payment-advances')->group(function () {
    Route::get('/available-by-student/{studentId}', [EnrollmentPaymentAdvanceController::class, 'getAvailableByStudentId'])->name('ap.enrollment-payment-advances.availableByStudent');
    Route::post('/save',                            [EnrollmentPaymentAdvanceController::class, 'save'])                   ->name('ap.enrollment-payment-advances.save');
    Route::delete('/delete/{id}',                   [EnrollmentPaymentAdvanceController::class, 'delete'])                 ->name('ap.enrollment-payment-advances.delete');
});