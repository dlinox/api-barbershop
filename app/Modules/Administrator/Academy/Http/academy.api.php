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
use App\Modules\Administrator\Academy\Http\Controllers\StudentGuardianController;
use App\Modules\Administrator\Academy\Http\Controllers\EnrollmentPaymentAdvanceController;

Route::middleware(['auth:api', 'super_admin'])->prefix('/branches')->group(function () {
    Route::post('/data-table', [BranchController::class, 'dataTable'])->name('branches.dataTable');
    Route::post('/save', [BranchController::class, 'save'])->name('branches.save');
    Route::get('/select-items', [BranchController::class, 'selectItems'])->name('branches.selectItems');
    Route::delete('/delete/{id}', [BranchController::class, 'delete'])->name('branches.delete');
    Route::delete('/delete-logo/{id}', [BranchController::class, 'deleteLogo'])->name('branches.deleteLogo');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/rooms')->group(function () {
    Route::post('/data-table', [RoomController::class, 'dataTable'])->name('rooms.dataTable');
    Route::post('/save', [RoomController::class, 'save'])->name('rooms.save');
    Route::delete('/delete/{id}', [RoomController::class, 'delete'])->name('rooms.delete');
    Route::get('/select-items', [RoomController::class, 'selectItems'])->name('rooms.selectItems');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/levels')->group(function () {
    Route::post('/data-table', [LevelController::class, 'dataTable'])->name('levels.dataTable');
    Route::post('/save', [LevelController::class, 'save'])->name('levels.save');
    Route::get('/select-items', [LevelController::class, 'selectItems'])->name('levels.selectItems');
    Route::delete('/delete/{id}', [LevelController::class, 'delete'])->name('levels.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/schedules')->group(function () {
    Route::post('/data-table', [ScheduleController::class, 'dataTable'])->name('schedules.dataTable');
    Route::post('/save', [ScheduleController::class, 'save'])->name('schedules.save');
    Route::get('/select-items', [ScheduleController::class, 'selectItems'])->name('schedules.selectItems');
    Route::delete('/delete/{id}', [ScheduleController::class, 'delete'])->name('schedules.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/groups')->group(function () {
    Route::post('/data-table', [GroupController::class, 'dataTable'])->name('groups.dataTable');
    Route::post('/save', [GroupController::class, 'save'])->name('groups.save');
    Route::delete('/delete/{id}', [GroupController::class, 'delete'])->name('groups.delete');
    Route::patch('/cancel/{id}', [GroupController::class, 'cancel'])->name('groups.cancel');
    Route::post('/assign-teacher', [GroupController::class, 'assignTeacher'])->name('groups.assignTeacher');
    Route::get('/check-teacher/{groupId}/{teacherId}', [GroupController::class, 'checkTeacher'])->name('groups.checkTeacher');
    Route::get('/select-items', [GroupController::class, 'selectItems'])->name('groups.selectItems');
    Route::get('/enrollment/get-availables/{studentId}', [GroupController::class, 'getAvailableEnrollmentGroups'])->name('groups.getAvailableEnrollmentGroups'); // Dejado sin protección para que los clientes puedan acceder a la lista disponible de grupos al enrolar. Revisar si aplica permission adicional.
    Route::get('/get-active-and-upcoming', [GroupController::class, 'getActiveAndUpcoming'])->name('groups.getActiveAndUpcoming'); // Exponer listados completos se protege
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/students')->group(function () {
    Route::post('/data-table', [StudentController::class, 'dataTable'])->name('students.dataTable');
    Route::post('/user-data-table', [StudentController::class, 'userDataTable'])->name('students.userDataTable');
    Route::post('/save', [StudentController::class, 'save'])->name('students.save');
    Route::post('/save-user', [StudentController::class, 'saveUser'])->name('students.saveUser');
    Route::get('/select-async-items', [StudentController::class, 'selectAsyncItems'])->name('students.selectAsyncItems');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/teachers')->group(function () {
    Route::post('/data-table', [TeacherController::class, 'dataTable'])->name('teachers.dataTable');
    Route::post('/user-data-table', [TeacherController::class, 'userDataTable'])->name('teachers.userDataTable');
    Route::post('/payment-summary', [TeacherController::class, 'paymentSummaryDataTable'])->name('teachers.paymentSummary');
    Route::post('/payment-calculation/{teacherId}', [TeacherController::class, 'paymentCalculation'])->name('teachers.paymentCalculation');
    Route::post('/save', [TeacherController::class, 'save'])->name('teachers.save');
    Route::post('/save-user', [TeacherController::class, 'saveUser'])->name('teachers.saveUser');
    Route::get('/select-async-items', [TeacherController::class, 'selectAsyncItems'])->name('teachers.selectAsyncItems');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/enrollments')->group(function () {
    Route::post('/data-table', [EnrollmentController::class, 'dataTable'])->name('enrollments.dataTable');
    Route::post('/save', [EnrollmentController::class, 'save'])->name('enrollments.save');
    Route::post('/save-without-payment', [EnrollmentController::class, 'saveWithoutPayment'])->name('enrollments.saveWithoutPayment');
    Route::post('/update', [EnrollmentController::class, 'update'])->name('enrollments.update');
    Route::get('/get/{id}', [EnrollmentController::class, 'getEnrollment'])->name('enrollments.getEnrollment');
    Route::post('/register-payment', [EnrollmentController::class, 'registerPayment'])->name('enrollments.registerPayment');
    Route::get('/generate-pdf/{id}', [EnrollmentController::class, 'generatePdf'])->name('enrollments.generatePdf');
    Route::get('/detail/{id}', [EnrollmentController::class, 'detail'])->name('enrollments.detail');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/attendances')->group(function () {
    Route::post('/data-table', [AttendanceController::class, 'dataTable'])->name('attendances.dataTable');
    Route::post('/save', [AttendanceController::class, 'save'])->name('attendances.save');
    Route::get('/get-groups-by-attendance', [AttendanceController::class, 'getGroupsByAttendance'])->name('attendances.getGroupsByAttendance');
    Route::post('/start-attendance-deadline', [AttendanceController::class, 'startAttendanceDeadline'])->name('attendances.startAttendanceDeadline');
    Route::post('/update-attendance-deadline', [AttendanceController::class, 'updateAttendanceDeadline'])->name('attendances.updateAttendanceDeadline');
    Route::post('/register-attendance-by-document', [AttendanceController::class, 'registerAttendanceByDocument'])->name('attendances.registerAttendanceByDocument');
    Route::post('/register-attendance-by-code', [AttendanceController::class, 'registerAttendanceByCode'])->name('attendances.registerAttendanceByCode');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/enrollment-payments')->group(function () {
    Route::post('/data-table', [EnrollmentPaymentController::class, 'dataTable'])->name('enrollment-payments.dataTable');
    Route::post('/save', [EnrollmentPaymentController::class, 'save'])->name('enrollment-payments.save');
    Route::delete('/delete/{id}', [EnrollmentPaymentController::class, 'delete'])->name('enrollment-payments.delete');
    Route::get('/history-by-enrollment-id/{enrollmentId}', [EnrollmentPaymentController::class, 'historyByEnrollmentId'])->name('enrollment-payments.historyByEnrollmentId'); // Exponer pagos e historial a detalle requiere protección
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/materials')->group(function () {
    Route::post('/data-table', [MaterialController::class, 'dataTable'])->name('materials.dataTable');
    Route::post('/save', [MaterialController::class, 'save'])->name('materials.save');
    Route::get('/select-items', [MaterialController::class, 'selectItems'])->name('materials.selectItems');
    Route::delete('/delete/{id}', [MaterialController::class, 'delete'])->name('materials.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/teacher-attendances')->group(function () {
    Route::post('/data-table/{date?}', [TeacherAttendanceController::class, 'dataTable'])->name('teacher-attendances.dataTable');
    Route::post('/register-check-in', [TeacherAttendanceController::class, 'registerCheckIn'])->name('teacher-attendances.registerCheckIn');
    Route::post('/register-check-out', [TeacherAttendanceController::class, 'registerCheckOut'])->name('teacher-attendances.registerCheckOut');
    Route::post('/register-absent', [TeacherAttendanceController::class, 'registerAbsent'])->name('teacher-attendances.registerAbsent');
    Route::post('/update', [TeacherAttendanceController::class, 'update'])->name('teacher-attendances.update');
    Route::post('/generate-qr-code', [TeacherAttendanceController::class, 'generateQrCode'])->name('teacher-attendances.generateQrCode');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/student-guardians')->group(function () {
    Route::get('/by-student/{studentId}', [StudentGuardianController::class, 'findByStudentId'])->name('student-guardians.byStudent');
    Route::post('/save', [StudentGuardianController::class, 'save'])->name('student-guardians.save');
    Route::delete('/delete/{id}', [StudentGuardianController::class, 'delete'])->name('student-guardians.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/enrollment-payment-advances')->group(function () {
    Route::get('/available-by-student/{studentId}', [EnrollmentPaymentAdvanceController::class, 'getAvailableByStudentId'])->name('enrollment-payment-advances.availableByStudent');
    Route::post('/save', [EnrollmentPaymentAdvanceController::class, 'save'])->name('enrollment-payment-advances.save');
    Route::delete('/delete/{id}', [EnrollmentPaymentAdvanceController::class, 'delete'])->name('enrollment-payment-advances.delete');
});
