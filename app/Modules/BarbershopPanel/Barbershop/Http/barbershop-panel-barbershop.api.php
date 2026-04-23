<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BarbershopPanel\Barbershop\Http\Controllers\ClientController;
use App\Modules\BarbershopPanel\Barbershop\Http\Controllers\BarberController;
use App\Modules\BarbershopPanel\Barbershop\Http\Controllers\BranchController;
use App\Modules\BarbershopPanel\Barbershop\Http\Controllers\CategoryController;
use App\Modules\BarbershopPanel\Barbershop\Http\Controllers\ServiceController;
use App\Modules\BarbershopPanel\Barbershop\Http\Controllers\ReservationController;
use App\Modules\BarbershopPanel\Barbershop\Http\Controllers\TicketController;
use App\Modules\BarbershopPanel\Barbershop\Http\Controllers\BarberAttendanceController;

Route::middleware(['auth:api'])->prefix('/barbershop-panel/clients')->group(function () {
    Route::post('/data-table',        [ClientController::class, 'dataTable'])       ->name('bp.clients.dataTable');
    Route::post('/save',              [ClientController::class, 'save'])             ->name('bp.clients.save');
    Route::get('/select-async-items', [ClientController::class, 'selectAsyncItems'])->name('bp.clients.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbers')->group(function () {
    Route::post('/data-table',        [BarberController::class, 'dataTable'])       ->name('bp.barbers.dataTable');
    Route::post('/save',              [BarberController::class, 'save'])             ->name('bp.barbers.save');
    Route::get('/select-async-items', [BarberController::class, 'selectAsyncItems'])->name('bp.barbers.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbershop-branches')->group(function () {
    Route::post('/data-table',    [BranchController::class, 'dataTable'])  ->name('bp.barbershop-branches.dataTable');
    Route::post('/save',          [BranchController::class, 'save'])       ->name('bp.barbershop-branches.save');
    Route::get('/select-items',   [BranchController::class, 'selectItems'])->name('bp.barbershop-branches.selectItems');
    Route::delete('/delete/{id}', [BranchController::class, 'delete'])     ->name('bp.barbershop-branches.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbershop-categories')->group(function () {
    Route::post('/data-table',    [CategoryController::class, 'dataTable'])  ->name('bp.barbershop-categories.dataTable');
    Route::post('/save',          [CategoryController::class, 'save'])       ->name('bp.barbershop-categories.save');
    Route::get('/select-items',   [CategoryController::class, 'selectItems'])->name('bp.barbershop-categories.selectItems');
    Route::delete('/delete/{id}', [CategoryController::class, 'delete'])     ->name('bp.barbershop-categories.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbershop-services')->group(function () {
    Route::post('/data-table',    [ServiceController::class, 'dataTable'])  ->name('bp.barbershop-services.dataTable');
    Route::post('/save',          [ServiceController::class, 'save'])       ->name('bp.barbershop-services.save');
    Route::get('/select-items',   [ServiceController::class, 'selectItems'])->name('bp.barbershop-services.selectItems');
    Route::delete('/delete/{id}', [ServiceController::class, 'delete'])     ->name('bp.barbershop-services.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbershop-reservations')->group(function () {
    Route::post('/data-table',    [ReservationController::class, 'dataTable'])->name('bp.barbershop-reservations.dataTable');
    Route::post('/save',          [ReservationController::class, 'save'])     ->name('bp.barbershop-reservations.save');
    Route::delete('/delete/{id}', [ReservationController::class, 'delete'])   ->name('bp.barbershop-reservations.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbershop-tickets')->group(function () {
    Route::post('/data-table',                      [TicketController::class, 'dataTable'])      ->name('bp.barbershop-tickets.dataTable');
    Route::get('/get/{id}',                         [TicketController::class, 'getById'])        ->name('bp.barbershop-tickets.getById');
    Route::post('/save',                            [TicketController::class, 'save'])           ->name('bp.barbershop-tickets.save');
    Route::post('/cancel/{id}',                     [TicketController::class, 'cancel'])         ->name('bp.barbershop-tickets.cancel');
    Route::get('/tickets-overview/{cashSessionId}', [TicketController::class, 'ticketsOverview'])->name('bp.barbershop-tickets.ticketsOverview');
    Route::get('/waiting-queue/{cashSessionId}',    [TicketController::class, 'waitingQueue'])   ->name('bp.barbershop-tickets.waitingQueue');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barber-attendances')->group(function () {
    Route::post('/data-table/{date?}', [BarberAttendanceController::class, 'dataTable'])       ->name('bp.barber-attendances.dataTable');
    Route::post('/register-check-in',  [BarberAttendanceController::class, 'registerCheckIn']) ->name('bp.barber-attendances.registerCheckIn');
    Route::post('/register-check-out', [BarberAttendanceController::class, 'registerCheckOut'])->name('bp.barber-attendances.registerCheckOut');
    Route::post('/register-absent',    [BarberAttendanceController::class, 'registerAbsent'])  ->name('bp.barber-attendances.registerAbsent');
    Route::post('/update',             [BarberAttendanceController::class, 'update'])           ->name('bp.barber-attendances.update');
    Route::post('/generate-qr-code',   [BarberAttendanceController::class, 'generateQrCode'])  ->name('bp.barber-attendances.generateQrCode');
});