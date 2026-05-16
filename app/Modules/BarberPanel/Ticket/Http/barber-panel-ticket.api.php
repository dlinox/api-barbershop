<?php

use App\Modules\BarberPanel\Ticket\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/barber-panel/tickets')->group(function () {
    Route::post('/data-table', [TicketController::class, 'dataTable']);
    Route::get('/tickets-overview/{cashSessionId}', [TicketController::class, 'ticketsOverview']);
    Route::post('/save', [TicketController::class, 'save']);
    Route::get('/get/{id}', [TicketController::class, 'getById']);
    Route::post('/cancel/{id}', [TicketController::class, 'cancel']);
    Route::get('/waiting-queue/{cashSessionId}', [TicketController::class, 'waitingQueue']);
    Route::get('/services-by-infrastructure/{infrastructureId}', [TicketController::class, 'servicesByInfrastructure']);
    Route::get('/generate-pdf/{incomeId}', [TicketController::class, 'generatePdf']);
});
