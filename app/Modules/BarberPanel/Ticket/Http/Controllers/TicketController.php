<?php

namespace App\Modules\BarberPanel\Ticket\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Barbershop\Http\Resources\Service\ServiceSelectItemByInfrastructureResource;
use App\Modules\BarberPanel\Ticket\Http\Requests\TicketRequest;
use App\Modules\BarberPanel\Ticket\Http\Resources\TicketDataTableItemResource;
use App\Modules\BarberPanel\Ticket\Http\Resources\TicketResource;
use App\Modules\BarberPanel\Ticket\Http\Resources\WaitingQueueTicketResource;
use App\Modules\BarberPanel\Ticket\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController
{
    public function __construct(
        private readonly TicketService $service,
    ) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->service->dataTable($request);
        $items['data'] = TicketDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function ticketsOverview(int $cashSessionId): JsonResponse
    {
        $overview = $this->service->ticketsOverview($cashSessionId);
        return ApiResponse::success($overview);
    }

    public function save(TicketRequest $request): JsonResponse
    {
        $result = $this->service->save($request->validated());
        return ApiResponse::success([
            'incomeId' => $result['incomeId'],
        ]);
    }

    public function getById(int $id): JsonResponse
    {
        $ticket = $this->service->findById($id);
        return ApiResponse::success(new TicketResource($ticket));
    }

    public function cancel(int $id): JsonResponse
    {
        $this->service->cancel($id);
        return ApiResponse::success(null, 'Ticket cancelado correctamente');
    }

    public function waitingQueue(int $cashSessionId): JsonResponse
    {
        $items = $this->service->waitingQueue($cashSessionId);
        return ApiResponse::success(WaitingQueueTicketResource::collection($items));
    }

    public function servicesByInfrastructure(int $infrastructureId): JsonResponse
    {
        $items = $this->service->getServicesByInfrastructure($infrastructureId);
        $items = ServiceSelectItemByInfrastructureResource::collection($items);
        return ApiResponse::success($items);
    }
}
