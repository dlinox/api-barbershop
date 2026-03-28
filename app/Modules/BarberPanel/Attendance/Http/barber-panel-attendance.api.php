<?php

use App\Modules\BarberPanel\Attendance\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/barber-panel/attendances')->group(function () {
    Route::post('/data-table', [AttendanceController::class, 'dataTable']);
});
