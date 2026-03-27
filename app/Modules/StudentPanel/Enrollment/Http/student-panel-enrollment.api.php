<?php

use App\Modules\StudentPanel\Enrollment\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/student-panel/enrollments')->group(function () {
    Route::get('/list', [EnrollmentController::class, 'list']);
});
