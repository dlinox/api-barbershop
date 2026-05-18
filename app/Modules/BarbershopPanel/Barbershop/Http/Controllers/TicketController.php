<?php

namespace App\Modules\BarbershopPanel\Barbershop\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\BarbershopPanel\Barbershop\Services\TicketService;
use App\Modules\BarbershopPanel\Barbershop\Http\Requests\TicketRequest;
use App\Modules\Administrator\Barbershop\Http\Resources\Ticket\TicketDataTableItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Ticket\TicketResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Ticket\WaitingQueueTicketResource;

class TicketController
{
    public function __construct(private TicketService $ticketService) {}

    public function dataTable(Request $request)
    {
        $items = $this->ticketService->dataTable($request);
        $items['data'] = TicketDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(int $id)
    {
        $ticket = $this->ticketService->findById($id);
        return ApiResponse::success(new TicketResource($ticket));
    }

    public function save(TicketRequest $request)
    {
        $result = $this->ticketService->save($request->validated());
        return ApiResponse::success($result, 'Ticket guardado correctamente');
    }

    public function cancel(int $id)
    {
        $this->ticketService->cancel($id);
        return ApiResponse::success(null, 'Ticket cancelado correctamente');
    }

    public function delete(int $id)
    {
        $this->ticketService->delete($id);
        return ApiResponse::success(null, 'Ticket eliminado correctamente');
    }

    public function ticketsOverview(int $cashSessionId)
    {
        $overview = $this->ticketService->ticketsOverview($cashSessionId);
        return ApiResponse::success($overview);
    }

    public function waitingQueue(int $cashSessionId)
    {
        $items = $this->ticketService->waitingQueue($cashSessionId);
        return ApiResponse::success(WaitingQueueTicketResource::collection($items));
    }
}