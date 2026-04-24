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
    Route::post('/data-table',        [ClientController::class, 'dataTable'])       ->name('bp.clients.dataTable')       ->middleware('permission:barbershop_panel.persons.client');
    Route::post('/save',              [ClientController::class, 'save'])             ->name('bp.clients.save')             ->middleware('permission:barbershop_panel.persons.client');
    Route::get('/select-async-items', [ClientController::class, 'selectAsyncItems'])->name('bp.clients.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbers')->group(function () {
    Route::post('/data-table',        [BarberController::class, 'dataTable'])       ->name('bp.barbers.dataTable')       ->middleware('permission:barbershop_panel.persons.barber');
    Route::post('/save',              [BarberController::class, 'save'])             ->name('bp.barbers.save')             ->middleware('permission:barbershop_panel.persons.barber');
    Route::get('/select-async-items', [BarberController::class, 'selectAsyncItems'])->name('bp.barbers.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbershop-branches')->group(function () {
    Route::post('/data-table',    [BranchController::class, 'dataTable'])  ->name('bp.barbershop-branches.dataTable');
    Route::post('/save',          [BranchController::class, 'save'])       ->name('bp.barbershop-branches.save');
    Route::get('/select-items',   [BranchController::class, 'selectItems'])->name('bp.barbershop-branches.selectItems');
    Route::delete('/delete/{id}', [BranchController::class, 'delete'])     ->name('bp.barbershop-branches.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbershop-categories')->group(function () {
    Route::post('/data-table',    [CategoryController::class, 'dataTable'])  ->name('bp.barbershop-categories.dataTable') ->middleware('permission:barbershop_panel.config.category');
    Route::post('/save',          [CategoryController::class, 'save'])       ->name('bp.barbershop-categories.save')      ->middleware('permission:barbershop_panel.config.category');
    Route::get('/select-items',   [CategoryController::class, 'selectItems'])->name('bp.barbershop-categories.selectItems');
    Route::delete('/delete/{id}', [CategoryController::class, 'delete'])     ->name('bp.barbershop-categories.delete')    ->middleware('permission:barbershop_panel.config.category');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbershop-services')->group(function () {
    Route::post('/data-table',    [ServiceController::class, 'dataTable'])  ->name('bp.barbershop-services.dataTable') ->middleware('permission:barbershop_panel.config.service');
    Route::post('/save',          [ServiceController::class, 'save'])       ->name('bp.barbershop-services.save')      ->middleware('permission:barbershop_panel.config.service');
    Route::get('/select-items',   [ServiceController::class, 'selectItems'])->name('bp.barbershop-services.selectItems');
    Route::delete('/delete/{id}', [ServiceController::class, 'delete'])     ->name('bp.barbershop-services.delete')    ->middleware('permission:barbershop_panel.config.service');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbershop-reservations')->group(function () {
    Route::post('/data-table',    [ReservationController::class, 'dataTable'])->name('bp.barbershop-reservations.dataTable') ->middleware('permission:barbershop_panel.reception.ticket');
    Route::post('/save',          [ReservationController::class, 'save'])     ->name('bp.barbershop-reservations.save')      ->middleware('permission:barbershop_panel.reception.ticket');
    Route::delete('/delete/{id}', [ReservationController::class, 'delete'])   ->name('bp.barbershop-reservations.delete')    ->middleware('permission:barbershop_panel.reception.ticket');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barbershop-tickets')->group(function () {
    Route::post('/data-table',                      [TicketController::class, 'dataTable'])      ->name('bp.barbershop-tickets.dataTable')      ->middleware('permission:barbershop_panel.reception.ticket');
    Route::get('/get/{id}',                         [TicketController::class, 'getById'])        ->name('bp.barbershop-tickets.getById')        ->middleware('permission:barbershop_panel.reception.ticket');
    Route::post('/save',                            [TicketController::class, 'save'])           ->name('bp.barbershop-tickets.save')           ->middleware('permission:barbershop_panel.reception.ticket');
    Route::post('/cancel/{id}',                     [TicketController::class, 'cancel'])         ->name('bp.barbershop-tickets.cancel')         ->middleware('permission:barbershop_panel.reception.ticket');
    Route::get('/tickets-overview/{cashSessionId}', [TicketController::class, 'ticketsOverview'])->name('bp.barbershop-tickets.ticketsOverview')->middleware('permission:barbershop_panel.reception.ticket');
    Route::get('/waiting-queue/{cashSessionId}',    [TicketController::class, 'waitingQueue'])   ->name('bp.barbershop-tickets.waitingQueue')   ->middleware('permission:barbershop_panel.reception.ticket');
});

Route::middleware(['auth:api'])->prefix('/barbershop-panel/barber-attendances')->group(function () {
    Route::post('/data-table/{date?}', [BarberAttendanceController::class, 'dataTable'])       ->name('bp.barber-attendances.dataTable')       ->middleware('permission:barbershop_panel.attendance.barber');
    Route::post('/register-check-in',  [BarberAttendanceController::class, 'registerCheckIn']) ->name('bp.barber-attendances.registerCheckIn') ->middleware('permission:barbershop_panel.attendance.barber');
    Route::post('/register-check-out', [BarberAttendanceController::class, 'registerCheckOut'])->name('bp.barber-attendances.registerCheckOut')->middleware('permission:barbershop_panel.attendance.barber');
    Route::post('/register-absent',    [BarberAttendanceController::class, 'registerAbsent'])  ->name('bp.barber-attendances.registerAbsent')  ->middleware('permission:barbershop_panel.attendance.barber');
    Route::post('/update',             [BarberAttendanceController::class, 'update'])           ->name('bp.barber-attendances.update')           ->middleware('permission:barbershop_panel.attendance.barber');
    Route::post('/generate-qr-code',   [BarberAttendanceController::class, 'generateQrCode'])  ->name('bp.barber-attendances.generateQrCode')  ->middleware('permission:barbershop_panel.attendance.barber');
});