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
    Route::post('/data-table',    [RoomController::class, 'dataTable'])  ->name('ap.rooms.dataTable') ->middleware('permission:academy_panel.config.room');
    Route::post('/save',          [RoomController::class, 'save'])       ->name('ap.rooms.save')      ->middleware('permission:academy_panel.config.room');
    Route::delete('/delete/{id}', [RoomController::class, 'delete'])     ->name('ap.rooms.delete')    ->middleware('permission:academy_panel.config.room');
    Route::get('/select-items',   [RoomController::class, 'selectItems'])->name('ap.rooms.selectItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/levels')->group(function () {
    Route::post('/data-table',    [LevelController::class, 'dataTable'])  ->name('ap.levels.dataTable') ->middleware('permission:academy_panel.config.level');
    Route::post('/save',          [LevelController::class, 'save'])       ->name('ap.levels.save')      ->middleware('permission:academy_panel.config.level');
    Route::delete('/delete/{id}', [LevelController::class, 'delete'])     ->name('ap.levels.delete')    ->middleware('permission:academy_panel.config.level');
    Route::get('/select-items',   [LevelController::class, 'selectItems'])->name('ap.levels.selectItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/schedules')->group(function () {
    Route::post('/data-table',    [ScheduleController::class, 'dataTable'])  ->name('ap.schedules.dataTable') ->middleware('permission:academy_panel.config.schedule');
    Route::post('/save',          [ScheduleController::class, 'save'])       ->name('ap.schedules.save')      ->middleware('permission:academy_panel.config.schedule');
    Route::delete('/delete/{id}', [ScheduleController::class, 'delete'])     ->name('ap.schedules.delete')    ->middleware('permission:academy_panel.config.schedule');
    Route::get('/select-items',   [ScheduleController::class, 'selectItems'])->name('ap.schedules.selectItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/groups')->group(function () {
    Route::post('/data-table',                               [GroupController::class, 'dataTable'])                   ->name('ap.groups.dataTable')                   ->middleware('permission:academy_panel.group.view');
    Route::post('/save',                                     [GroupController::class, 'save'])                        ->name('ap.groups.save')                        ->middleware('permission:academy_panel.group.view');
    Route::delete('/delete/{id}',                            [GroupController::class, 'delete'])                      ->name('ap.groups.delete')                      ->middleware('permission:academy_panel.group.view');
    Route::patch('/cancel/{id}',                             [GroupController::class, 'cancel'])                      ->name('ap.groups.cancel')                      ->middleware('permission:academy_panel.group.view');
    Route::post('/assign-teacher',                           [GroupController::class, 'assignTeacher'])               ->name('ap.groups.assignTeacher')               ->middleware('permission:academy_panel.group.view');
    Route::get('/check-teacher/{groupId}/{teacherId}',       [GroupController::class, 'checkTeacher'])                ->name('ap.groups.checkTeacher')                ->middleware('permission:academy_panel.group.view');
    Route::get('/select-items',                              [GroupController::class, 'selectItems'])                 ->name('ap.groups.selectItems');
    Route::get('/enrollment/get-availables/{studentId}',     [GroupController::class, 'getAvailableEnrollmentGroups'])->name('ap.groups.getAvailableEnrollmentGroups');
    Route::get('/get-active-and-upcoming',                   [GroupController::class, 'getActiveAndUpcoming'])        ->name('ap.groups.getActiveAndUpcoming');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/students')->group(function () {
    Route::post('/data-table',        [StudentController::class, 'dataTable'])       ->name('ap.students.dataTable') ->middleware('permission:academy_panel.students.db');
    Route::post('/save',              [StudentController::class, 'save'])            ->name('ap.students.save')      ->middleware('permission:academy_panel.students.db');
    Route::get('/select-async-items', [StudentController::class, 'selectAsyncItems'])->name('ap.students.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/teachers')->group(function () {
    Route::post('/data-table',        [TeacherController::class, 'dataTable'])       ->name('ap.teachers.dataTable') ->middleware('permission:academy_panel.teachers.db');
    Route::post('/save',              [TeacherController::class, 'save'])            ->name('ap.teachers.save')      ->middleware('permission:academy_panel.teachers.db');
    Route::get('/select-async-items', [TeacherController::class, 'selectAsyncItems'])->name('ap.teachers.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/enrollments')->group(function () {
    Route::post('/data-table',           [EnrollmentController::class, 'dataTable'])          ->name('ap.enrollments.dataTable')          ->middleware('permission:academy_panel.students.enrollment');
    Route::post('/save',                 [EnrollmentController::class, 'save'])               ->name('ap.enrollments.save')               ->middleware('permission:academy_panel.students.enrollment');
    Route::post('/save-without-payment', [EnrollmentController::class, 'saveWithoutPayment']) ->name('ap.enrollments.saveWithoutPayment') ->middleware('permission:academy_panel.students.enrollment');
    Route::post('/update',               [EnrollmentController::class, 'update'])             ->name('ap.enrollments.update')             ->middleware('permission:academy_panel.students.enrollment');
    Route::get('/get/{id}',              [EnrollmentController::class, 'getEnrollment'])      ->name('ap.enrollments.getEnrollment')      ->middleware('permission:academy_panel.students.enrollment');
    Route::post('/register-payment',     [EnrollmentController::class, 'registerPayment'])    ->name('ap.enrollments.registerPayment')    ->middleware('permission:academy_panel.students.enrollment');
    Route::get('/generate-pdf/{id}',     [EnrollmentController::class, 'generatePdf'])        ->name('ap.enrollments.generatePdf')        ->middleware('permission:academy_panel.students.enrollment');
    Route::get('/detail/{id}',           [EnrollmentController::class, 'detail'])             ->name('ap.enrollments.detail')             ->middleware('permission:academy_panel.students.enrollment');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/attendances')->group(function () {
    Route::post('/data-table',                      [AttendanceController::class, 'dataTable'])                   ->name('ap.attendances.dataTable')                   ->middleware('permission:academy_panel.students.attendance');
    Route::get('/get-groups-by-attendance',          [AttendanceController::class, 'getGroupsByAttendance'])       ->name('ap.attendances.getGroupsByAttendance')         ->middleware('permission:academy_panel.students.attendance');
    Route::post('/start-attendance-deadline',        [AttendanceController::class, 'startAttendanceDeadline'])     ->name('ap.attendances.startAttendanceDeadline')       ->middleware('permission:academy_panel.students.attendance');
    Route::post('/update-attendance-deadline',       [AttendanceController::class, 'updateAttendanceDeadline'])    ->name('ap.attendances.updateAttendanceDeadline')      ->middleware('permission:academy_panel.students.attendance');
    Route::post('/register-attendance-by-document',  [AttendanceController::class, 'registerAttendanceByDocument'])->name('ap.attendances.registerAttendanceByDocument')  ->middleware('permission:academy_panel.students.attendance');
    Route::post('/register-attendance-by-code',      [AttendanceController::class, 'registerAttendanceByCode'])    ->name('ap.attendances.registerAttendanceByCode')       ->middleware('permission:academy_panel.students.attendance');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/enrollment-payments')->group(function () {
    Route::post('/data-table',                             [EnrollmentPaymentController::class, 'dataTable'])             ->name('ap.enrollment-payments.dataTable')             ->middleware('permission:academy_panel.students.enrollment');
    Route::get('/history-by-enrollment-id/{enrollmentId}', [EnrollmentPaymentController::class, 'historyByEnrollmentId'])->name('ap.enrollment-payments.historyByEnrollmentId')  ->middleware('permission:academy_panel.students.enrollment');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/materials')->group(function () {
    Route::post('/data-table',    [MaterialController::class, 'dataTable'])  ->name('ap.materials.dataTable') ->middleware('permission:academy_panel.config.material');
    Route::post('/save',          [MaterialController::class, 'save'])       ->name('ap.materials.save')      ->middleware('permission:academy_panel.config.material');
    Route::delete('/delete/{id}', [MaterialController::class, 'delete'])     ->name('ap.materials.delete')    ->middleware('permission:academy_panel.config.material');
    Route::get('/select-items',   [MaterialController::class, 'selectItems'])->name('ap.materials.selectItems');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/teacher-attendances')->group(function () {
    Route::post('/data-table/{date?}', [TeacherAttendanceController::class, 'dataTable'])       ->name('ap.teacher-attendances.dataTable')       ->middleware('permission:academy_panel.teachers.attendance');
    Route::post('/register-check-in',  [TeacherAttendanceController::class, 'registerCheckIn']) ->name('ap.teacher-attendances.registerCheckIn') ->middleware('permission:academy_panel.teachers.attendance');
    Route::post('/register-check-out', [TeacherAttendanceController::class, 'registerCheckOut'])->name('ap.teacher-attendances.registerCheckOut')->middleware('permission:academy_panel.teachers.attendance');
    Route::post('/register-absent',    [TeacherAttendanceController::class, 'registerAbsent'])  ->name('ap.teacher-attendances.registerAbsent')  ->middleware('permission:academy_panel.teachers.attendance');
    Route::post('/update',             [TeacherAttendanceController::class, 'update'])           ->name('ap.teacher-attendances.update')           ->middleware('permission:academy_panel.teachers.attendance');
    Route::post('/generate-qr-code',   [TeacherAttendanceController::class, 'generateQrCode'])  ->name('ap.teacher-attendances.generateQrCode')  ->middleware('permission:academy_panel.teachers.attendance');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/student-guardians')->group(function () {
    Route::get('/by-student/{studentId}', [StudentGuardianController::class, 'findByStudentId'])->name('ap.student-guardians.byStudent') ->middleware('permission:academy_panel.students.db');
    Route::post('/save',                  [StudentGuardianController::class, 'save'])           ->name('ap.student-guardians.save')      ->middleware('permission:academy_panel.students.db');
    Route::delete('/delete/{id}',         [StudentGuardianController::class, 'delete'])         ->name('ap.student-guardians.delete')    ->middleware('permission:academy_panel.students.db');
});

Route::middleware(['auth:api'])->prefix('/academy-panel/enrollment-payment-advances')->group(function () {
    Route::get('/available-by-student/{studentId}', [EnrollmentPaymentAdvanceController::class, 'getAvailableByStudentId'])->name('ap.enrollment-payment-advances.availableByStudent') ->middleware('permission:academy_panel.students.enrollment');
    Route::post('/save',                            [EnrollmentPaymentAdvanceController::class, 'save'])                   ->name('ap.enrollment-payment-advances.save')               ->middleware('permission:academy_panel.students.enrollment');
    Route::delete('/delete/{id}',                   [EnrollmentPaymentAdvanceController::class, 'delete'])                 ->name('ap.enrollment-payment-advances.delete')             ->middleware('permission:academy_panel.students.enrollment');
});