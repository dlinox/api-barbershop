<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Barbershop\Http\Controllers\ClientController;
use App\Modules\Administrator\Barbershop\Http\Controllers\WorkerController;
use App\Modules\Administrator\Barbershop\Http\Controllers\BarberController;
use App\Modules\Administrator\Barbershop\Http\Controllers\BranchController;
use App\Modules\Administrator\Barbershop\Http\Controllers\CategoryController;
use App\Modules\Administrator\Barbershop\Http\Controllers\ServiceController;
use App\Modules\Administrator\Barbershop\Http\Controllers\ReservationController;
use App\Modules\Administrator\Barbershop\Http\Controllers\TicketController;

Route::middleware(['auth:api'])->prefix('/clients')->group(function () {
    Route::post('/data-table', [ClientController::class, 'dataTable'])->name('clients.dataTable')->middleware('permission:barbershop.client.view');
    Route::post('/save', [ClientController::class, 'save'])->name('clients.save')->middleware('permission:barbershop.client.create,barbershop.client.edit');
    Route::get('/select-async-items', [ClientController::class, 'selectAsyncItems'])->name('clients.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/workers')->group(function () {
    Route::post('/data-table', [WorkerController::class, 'dataTable'])->name('workers.dataTable')->middleware('permission:barbershop.worker.view');
    Route::post('/save', [WorkerController::class, 'save'])->name('workers.save')->middleware('permission:barbershop.worker.create,barbershop.worker.edit');
    Route::get('/select-async-items', [WorkerController::class, 'selectAsyncItems'])->name('workers.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/barbers')->group(function () {
    Route::post('/data-table', [BarberController::class, 'dataTable'])->name('barbers.dataTable')->middleware('permission:barbershop.barber.view');
    Route::post('/save', [BarberController::class, 'save'])->name('barbers.save')->middleware('permission:barbershop.barber.create,barbershop.barber.edit');
    Route::get('/select-async-items', [BarberController::class, 'selectAsyncItems'])->name('barbers.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/barbershop-branches')->group(function () {
    Route::post('/data-table', [BranchController::class, 'dataTable'])->name('barbershop-branches.dataTable')->middleware('permission:barbershop.branch.view');
    Route::post('/save', [BranchController::class, 'save'])->name('barbershop-branches.save')->middleware('permission:barbershop.branch.create,barbershop.branch.edit');
    Route::get('/select-items', [BranchController::class, 'selectItems'])->name('barbershop-branches.selectItems');
    Route::delete('/delete/{id}', [BranchController::class, 'delete'])->name('barbershop-branches.delete')->middleware('permission:barbershop.branch.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-categories')->group(function () {
    Route::post('/data-table', [CategoryController::class, 'dataTable'])->name('barbershop-categories.dataTable')->middleware('permission:barbershop.category.view');
    Route::post('/save', [CategoryController::class, 'save'])->name('barbershop-categories.save')->middleware('permission:barbershop.category.create,barbershop.category.edit');
    Route::get('/select-items', [CategoryController::class, 'selectItems'])->name('barbershop-categories.selectItems');
    Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('barbershop-categories.delete')->middleware('permission:barbershop.category.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-services')->group(function () {
    Route::post('/data-table', [ServiceController::class, 'dataTable'])->name('barbershop-services.dataTable')->middleware('permission:barbershop.service.view');
    Route::post('/save', [ServiceController::class, 'save'])->name('barbershop-services.save')->middleware('permission:barbershop.service.create,barbershop.service.edit');
    Route::get('/select-items', [ServiceController::class, 'selectItems'])->name('barbershop-services.selectItems');
    Route::get('/by-infrastructure/{infrastructureId}', [ServiceController::class, 'getByInfrastructure'])->name('barbershop-services.getByInfrastructure');
    Route::delete('/delete/{id}/{branchId}', [ServiceController::class, 'delete'])->name('barbershop-services.delete')->middleware('permission:barbershop.service.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-reservations')->group(function () {
    Route::post('/data-table', [ReservationController::class, 'dataTable'])->name('barbershop-reservations.dataTable')->middleware('permission:barbershop.reservation.view');
    Route::post('/save', [ReservationController::class, 'save'])->name('barbershop-reservations.save')->middleware('permission:barbershop.reservation.create');
    Route::delete('/delete/{id}', [ReservationController::class, 'delete'])->name('barbershop-reservations.delete')->middleware('permission:barbershop.reservation.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-tickets')->group(function () {
    Route::post('/data-table/{branchId}', [TicketController::class, 'dataTable'])->name('barbershop-tickets.dataTable')->middleware('permission:barbershop.ticket.view');
    Route::get('/get/{id}', [TicketController::class, 'getById'])->name('barbershop-tickets.getById')->middleware('permission:barbershop.ticket.view');
    Route::post('/save', [TicketController::class, 'save'])->name('barbershop-tickets.save')->middleware('permission:barbershop.ticket.create');
    Route::post('/cancel/{id}', [TicketController::class, 'cancel'])->name('barbershop-tickets.cancel')->middleware('permission:barbershop.ticket.cancel');
});
