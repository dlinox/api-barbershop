<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Barbershop\Http\Controllers\ClientController;
use App\Modules\Administrator\Barbershop\Http\Controllers\BarberController;
use App\Modules\Administrator\Barbershop\Http\Controllers\BranchController;
use App\Modules\Administrator\Barbershop\Http\Controllers\CategoryController;
use App\Modules\Administrator\Barbershop\Http\Controllers\ServiceController;
use App\Modules\Administrator\Barbershop\Http\Controllers\ReservationController;
use App\Modules\Administrator\Barbershop\Http\Controllers\TicketController;
use App\Modules\Administrator\Barbershop\Http\Controllers\BarberAttendanceController;

Route::middleware(['auth:api', 'super_admin'])->prefix('/clients')->group(function () {
    Route::post('/data-table', [ClientController::class, 'dataTable'])->name('clients.dataTable');
    Route::post('/save', [ClientController::class, 'save'])->name('clients.save');
    Route::get('/select-async-items', [ClientController::class, 'selectAsyncItems'])->name('clients.selectAsyncItems');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/barbers')->group(function () {
    Route::post('/data-table', [BarberController::class, 'dataTable'])->name('barbers.dataTable');
    Route::post('/payment-summary', [BarberController::class, 'paymentSummaryDataTable'])->name('barbers.paymentSummary');
    Route::post('/payment-calculation/{barberId}', [BarberController::class, 'paymentCalculation'])->name('barbers.paymentCalculation');
    Route::post('/save', [BarberController::class, 'save'])->name('barbers.save');
    Route::post('/user-data-table', [BarberController::class, 'userDataTable'])->name('barbers.userDataTable');
    Route::post('/save-user', [BarberController::class, 'saveUser'])->name('barbers.saveUser');
    Route::get('/select-async-items', [BarberController::class, 'selectAsyncItems'])->name('barbers.selectAsyncItems');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/barbershop-branches')->group(function () {
    Route::post('/data-table', [BranchController::class, 'dataTable'])->name('barbershop-branches.dataTable');
    Route::post('/save', [BranchController::class, 'save'])->name('barbershop-branches.save');
    Route::get('/select-items', [BranchController::class, 'selectItems'])->name('barbershop-branches.selectItems');
    Route::delete('/delete/{id}', [BranchController::class, 'delete'])->name('barbershop-branches.delete');
    Route::delete('/delete-logo/{id}', [BranchController::class, 'deleteLogo'])->name('barbershop-branches.deleteLogo');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/barbershop-categories')->group(function () {
    Route::post('/data-table', [CategoryController::class, 'dataTable'])->name('barbershop-categories.dataTable');
    Route::post('/save', [CategoryController::class, 'save'])->name('barbershop-categories.save');
    Route::get('/select-items', [CategoryController::class, 'selectItems'])->name('barbershop-categories.selectItems');
    Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('barbershop-categories.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/barbershop-services')->group(function () {
    Route::post('/data-table', [ServiceController::class, 'dataTable'])->name('barbershop-services.dataTable');
    Route::post('/save', [ServiceController::class, 'save'])->name('barbershop-services.save');
    Route::get('/select-items', [ServiceController::class, 'selectItems'])->name('barbershop-services.selectItems');
    Route::get('/by-infrastructure/{infrastructureId}', [ServiceController::class, 'getByInfrastructure'])->name('barbershop-services.getByInfrastructure');
    Route::delete('/delete/{id}', [ServiceController::class, 'delete'])->name('barbershop-services.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/barbershop-reservations')->group(function () {
    Route::post('/data-table', [ReservationController::class, 'dataTable'])->name('barbershop-reservations.dataTable');
    Route::post('/save', [ReservationController::class, 'save'])->name('barbershop-reservations.save');
    Route::delete('/delete/{id}', [ReservationController::class, 'delete'])->name('barbershop-reservations.delete');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/barbershop-tickets')->group(function () {
    Route::post('/data-table', [TicketController::class, 'dataTable'])->name('barbershop-tickets.dataTable');
    Route::get('/get/{id}', [TicketController::class, 'getById'])->name('barbershop-tickets.getById');
    Route::post('/save', [TicketController::class, 'save'])->name('barbershop-tickets.save');
    Route::post('/cancel/{id}', [TicketController::class, 'cancel'])->name('barbershop-tickets.cancel');
    Route::get('/tickets-overview/{cashSessionId}', [TicketController::class, 'ticketsOverview'])->name('barbershop-tickets.ticketsOverview');
    Route::get('/waiting-queue/{cashSessionId}', [TicketController::class, 'waitingQueue'])->name('barbershop-tickets.waitingQueue');
});

Route::middleware(['auth:api', 'super_admin'])->prefix('/barber-attendances')->group(function () {
    Route::post('/data-table/{date?}',    [BarberAttendanceController::class, 'dataTable'])       ->name('barber-attendances.dataTable')       ;
    Route::post('/register-check-in',     [BarberAttendanceController::class, 'registerCheckIn']) ->name('barber-attendances.registerCheckIn') ;
    Route::post('/register-check-out',    [BarberAttendanceController::class, 'registerCheckOut'])->name('barber-attendances.registerCheckOut');
    Route::post('/register-absent',       [BarberAttendanceController::class, 'registerAbsent'])  ->name('barber-attendances.registerAbsent')  ;
    Route::post('/update',                [BarberAttendanceController::class, 'update'])           ->name('barber-attendances.update')           ;
    Route::post('/generate-qr-code',      [BarberAttendanceController::class, 'generateQrCode'])  ->name('barber-attendances.generateQrCode')  ;
    Route::get('/history/{barberId}',      [BarberAttendanceController::class, 'history'])          ->name('barber-attendances.history')          ;
});
