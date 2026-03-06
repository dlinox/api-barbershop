
<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Barbershop\Http\Controllers\ClientController;
use App\Modules\Administrator\Barbershop\Http\Controllers\WorkerController;
use App\Modules\Administrator\Barbershop\Http\Controllers\BranchController;
use App\Modules\Administrator\Barbershop\Http\Controllers\CategoryController;
use App\Modules\Administrator\Barbershop\Http\Controllers\ServiceController;
use App\Modules\Administrator\Barbershop\Http\Controllers\ReservationController;
use App\Modules\Administrator\Barbershop\Http\Controllers\TicketController;

Route::middleware(['auth:api'])->prefix('/clients')->group(function () {
    Route::post('/data-table', [ClientController::class, 'dataTable'])->name('clients.dataTable');
    Route::post('/save', [ClientController::class, 'save'])->name('clients.save');
    Route::get('/select-async-items', [ClientController::class, 'selectAsyncItems'])->name('clients.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/workers')->group(function () {
    Route::post('/data-table', [WorkerController::class, 'dataTable'])->name('workers.dataTable');
    Route::post('/save', [WorkerController::class, 'save'])->name('workers.save');
    Route::get('/select-async-items', [WorkerController::class, 'selectAsyncItems'])->name('workers.selectAsyncItems');
});

Route::middleware(['auth:api'])->prefix('/barbershop-branches')->group(function () {
    Route::post('/data-table', [BranchController::class, 'dataTable'])->name('barbershop-branches.dataTable');
    Route::post('/save', [BranchController::class, 'save'])->name('barbershop-branches.save');
    Route::get('/select-items', [BranchController::class, 'selectItems'])->name('barbershop-branches.selectItems');
    Route::delete('/delete/{id}', [BranchController::class, 'delete'])->name('barbershop-branches.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-categories')->group(function () {
    Route::post('/data-table', [CategoryController::class, 'dataTable'])->name('barbershop-categories.dataTable');
    Route::post('/save', [CategoryController::class, 'save'])->name('barbershop-categories.save');
    Route::get('/select-items', [CategoryController::class, 'selectItems'])->name('barbershop-categories.selectItems');
    Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('barbershop-categories.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-services')->group(function () {
    Route::post('/data-table', [ServiceController::class, 'dataTable'])->name('barbershop-services.dataTable');
    Route::post('/save', [ServiceController::class, 'save'])->name('barbershop-services.save');
    Route::get('/select-items', [ServiceController::class, 'selectItems'])->name('barbershop-services.selectItems');
    Route::delete('/delete/{id}/{branchId}', [ServiceController::class, 'delete'])->name('barbershop-services.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-reservations')->group(function () {
    Route::post('/data-table', [ReservationController::class, 'dataTable'])->name('barbershop-reservations.dataTable');
    Route::post('/save', [ReservationController::class, 'save'])->name('barbershop-reservations.save');
    Route::delete('/delete/{id}', [ReservationController::class, 'delete'])->name('barbershop-reservations.delete');
});

Route::middleware(['auth:api'])->prefix('/barbershop-tickets')->group(function () {
    Route::post('/data-table/{branchId}', [TicketController::class, 'dataTable'])->name('barbershop-tickets.dataTable');
    Route::get('/get/{id}', [TicketController::class, 'getById'])->name('barbershop-tickets.getById');
    Route::post('/save', [TicketController::class, 'save'])->name('barbershop-tickets.save');
    Route::post('/cancel/{id}', [TicketController::class, 'cancel'])->name('barbershop-tickets.cancel');
});
