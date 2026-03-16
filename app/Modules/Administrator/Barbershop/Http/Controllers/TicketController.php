<?php

namespace App\Modules\Administrator\Barbershop\Http\Controllers;

use Illuminate\Http\Request;

use App\Common\Http\Responses\ApiResponse;

use App\Modules\Administrator\Barbershop\Services\TicketService;

use App\Modules\Administrator\Barbershop\Http\Requests\Ticket\TicketRequest;
use App\Modules\Administrator\Barbershop\Http\Resources\Ticket\TicketDataTableItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Ticket\TicketResource;

class TicketController
{

    public function __construct(
        private TicketService $ticketService
    ) {}

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
        $data = $request->validated();
        $this->ticketService->save($data);
        return ApiResponse::success(null, 'Ticket guardado correctamente');
    }

    public function cancel(int $id)
    {
        $this->ticketService->cancel($id);
        return ApiResponse::success(null, 'Ticket cancelado correctamente');
    }
}
