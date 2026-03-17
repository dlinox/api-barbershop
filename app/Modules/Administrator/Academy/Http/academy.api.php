<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Academy\Http\Controllers\BranchController;
use App\Modules\Administrator\Academy\Http\Controllers\LevelController;
use App\Modules\Administrator\Academy\Http\Controllers\RoomController;
use App\Modules\Administrator\Academy\Http\Controllers\ScheduleController;
use App\Modules\Administrator\Academy\Http\Controllers\GroupController;
use App\Modules\Administrator\Academy\Http\Controllers\StudentController;
use App\Modules\Administrator\Academy\Http\Controllers\TeacherController;
use App\Modules\Administrator\Academy\Http\Controllers\EnrollmentController;
use App\Modules\Administrator\Academy\Http\Controllers\AttendanceController;
use App\Modules\Administrator\Academy\Http\Controllers\EnrollmentPaymentController;
use App\Modules\Administrator\Academy\Http\Controllers\MaterialController;
use App\Modules\Administrator\Academy\Http\Controllers\TeacherAttendanceController;

Route::middleware(['auth:api'])->prefix('/branches')->group(function () {
    Route::post('/data-table', [BranchController::class, 'dataTable'])->name('branches.dataTable')->middleware('permission:academy.branch.view');
    Route::post('/save', [BranchController::class, 'save'])->name('branches.save')->middleware('permission:academy.branch.create,academy.branch.edit');
    Route::get('/select-items', [BranchController::class, 'selectItems'])->name('branches.selectItems');
    Route::delete('/delete/{id}', [BranchController::class, 'delete'])->name('branches.delete')->middleware('permission:academy.branch.delete');
});

Route::middleware(['auth:api'])->prefix('/rooms')->group(function () {
    Route::post('/data-table', [RoomController::class, 'dataTable'])->name('rooms.dataTable')->middleware('permission:academy.room.view');
    Route::post('/save', [RoomController::class, 'save'])->name('rooms.save')->middleware('permission:academy.room.create,academy.room.edit');
    Route::delete('/delete/{id}', [RoomController::class, 'delete'])->name('rooms.delete')->middleware('permission:academy.room.delete');
    Route::get('/select-items', [RoomController::class, 'selectItems'])->name('rooms.selectItems');
});

Route::middleware(['auth:api'])->prefix('/levels')->group(function () {
    Route::post('/data-table', [LevelController::class, 'dataTable'])->name('levels.dataTable')->middleware('permission:academy.level.view');
    Route::post('/save', [LevelController::class, 'save'])->name('levels.save')->middleware('permission:academy.level.create,academy.level.edit');
    Route::get('/select-items', [LevelController::class, 'selectItems'])->name('levels.selectItems');
    Route::delete('/delete/{id}', [LevelController::class, 'delete'])->name('levels.delete')->middleware('permission:academy.level.delete');
});

Route::middleware(['auth:api'])->prefix('/schedules')->group(function () {
    Route::post('/data-table', [ScheduleController::class, 'dataTable'])->name('schedules.dataTable')->middleware('permission:academy.schedule.view');
    Route::post('/save', [ScheduleController::class, 'save'])->name('schedules.save')->middleware('permission:academy.schedule.create,academy.schedule.edit');
    Route::get('/select-items', [ScheduleController::class, 'selectItems'])->name('schedules.selectItems');
    Route::delete('/delete/{id}', [ScheduleController::class, 'delete'])->name('schedules.delete')->middleware('permission:academy.schedule.delete');
});

Route::middleware(['auth:api'])->prefix('/groups')->group(function () {
    Route::post('/data-table', [GroupController::class, 'dataTable'])->name('groups.dataTable')->middleware('permission:academy.group.view');
    Route::post('/save', [GroupController::class, 'save'])->name('groups.save')->middleware('permission:academy.group.create,academy.group.edit');
    Route::delete('/delete/{id}', [GroupController::class, 'delete'])->name('groups.delete')->middleware('permission:academy.group.delete');
    Route::post('/assign-teacher', [GroupController::class, 'assignTeacher'])->name('groups.assignTeacher')->middleware('permission:academy.group.assign_teacher');
    Route::get('/select-items', [GroupController::class, 'selectItems'])->name('groups.selectItems');
    Route::get('/enrollment/get-availables/{studentId}', [GroupController::class, 'getAvailableEnrollmentGroups'])->name('groups.getAvailableEnrollmentGroups'); // Dejado sin protección para que los clientes puedan acceder a la lista disponible de grupos al enrolar. Revisar si aplica permission adicional.
    Route::get('/get-active-and-upcoming', [GroupController::class, 'getActiveAndUpcoming'])->name('groups.getActiveAndUpcoming')->middleware('permission:academy.group.view'); // Exponer listados completos se protege
});

Route::middleware(['auth:api'])->prefix('/students')->group(function () {
    Route::post('/data-table', [StudentController::class, 'dataTable'])->name('students.dataTable')->middleware('permission:academy.student.view');
    Route::post('/save', [StudentController::class, 'save'])->name('students.save')->middleware('permission:academy.student.create,academy.student.edit');
    Route::get('/select-async-items', [StudentController::class, 'selectAsyncItems'])->name('students.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/teachers')->group(function () {
    Route::post('/data-table', [TeacherController::class, 'dataTable'])->name('teachers.dataTable')->middleware('permission:academy.teacher.view');
    Route::post('/save', [TeacherController::class, 'save'])->name('teachers.save')->middleware('permission:academy.teacher.create,academy.teacher.edit');
    Route::get('/select-async-items', [TeacherController::class, 'selectAsyncItems'])->name('teachers.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/enrollments')->group(function () {
    Route::post('/data-table', [EnrollmentController::class, 'dataTable'])->name('enrollments.dataTable')->middleware('permission:academy.enrollment.view');
    Route::post('/save', [EnrollmentController::class, 'save'])->name('enrollments.save')->middleware('permission:academy.enrollment.create');
    Route::post('/update', [EnrollmentController::class, 'update'])->name('enrollments.update')->middleware('permission:academy.enrollment.edit');
    Route::get('/get/{id}', [EnrollmentController::class, 'getEnrollment'])->name('enrollments.getEnrollment')->middleware('permission:academy.enrollment.view'); // Detalle que carga de DB
    Route::post('/register-payment', [EnrollmentController::class, 'registerPayment'])->name('enrollments.registerPayment')->middleware('permission:academy.enrollment.register_payment');
});

Route::middleware(['auth:api'])->prefix('/attendances')->group(function () {
    Route::post('/data-table', [AttendanceController::class, 'dataTable'])->name('attendances.dataTable')->middleware('permission:academy.attendance.view');
    Route::post('/save', [AttendanceController::class, 'save'])->name('attendances.save')->middleware('permission:academy.attendance.create,academy.attendance.edit');
    Route::get('/get-groups-by-attendance', [AttendanceController::class, 'getGroupsByAttendance'])->name('attendances.getGroupsByAttendance')->middleware('permission:academy.attendance.view');
    Route::post('/start-attendance-deadline', [AttendanceController::class, 'startAttendanceDeadline'])->name('attendances.startAttendanceDeadline');
    Route::post('/update-attendance-deadline', [AttendanceController::class, 'updateAttendanceDeadline'])->name('attendances.updateAttendanceDeadline');
    Route::post('/register-attendance-by-document', [AttendanceController::class, 'registerAttendanceByDocument'])->name('attendances.registerAttendanceByDocument')->middleware('permission:academy.attendance.register');
    Route::post('/register-attendance-by-code', [AttendanceController::class, 'registerAttendanceByCode'])->name('attendances.registerAttendanceByCode')->middleware('permission:academy.attendance.register');
});

Route::middleware(['auth:api'])->prefix('/enrollment-payments')->group(function () {
    Route::post('/data-table', [EnrollmentPaymentController::class, 'dataTable'])->name('enrollment-payments.dataTable')->middleware('permission:academy.enrollment_payment.view');
    Route::post('/save', [EnrollmentPaymentController::class, 'save'])->name('enrollment-payments.save')->middleware('permission:academy.enrollment_payment.create,academy.enrollment_payment.edit');
    Route::delete('/delete/{id}', [EnrollmentPaymentController::class, 'delete'])->name('enrollment-payments.delete')->middleware('permission:academy.enrollment_payment.delete');
    Route::get('/history-by-enrollment-id/{enrollmentId}', [EnrollmentPaymentController::class, 'historyByEnrollmentId'])->name('enrollment-payments.historyByEnrollmentId')->middleware('permission:academy.enrollment_payment.view'); // Exponer pagos e historial a detalle requiere protección
});

Route::middleware(['auth:api'])->prefix('/materials')->group(function () {
    Route::post('/data-table', [MaterialController::class, 'dataTable'])->name('materials.dataTable')->middleware('permission:academy.material.view');
    Route::post('/save', [MaterialController::class, 'save'])->name('materials.save')->middleware('permission:academy.material.create,academy.material.edit');
    Route::get('/select-items', [MaterialController::class, 'selectItems'])->name('materials.selectItems');
    Route::delete('/delete/{id}', [MaterialController::class, 'delete'])->name('materials.delete')->middleware('permission:academy.material.delete');
});

Route::middleware(['auth:api'])->prefix('/teacher-attendances')->group(function () {
    Route::post('/data-table/{date?}', [TeacherAttendanceController::class, 'dataTable'])->name('teacher-attendances.dataTable')->middleware('permission:academy.teacher_attendance.view');
});
