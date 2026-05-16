<?php

namespace App\Modules\BarberPanel\Ticket\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Models\Treasury\Income;
use App\Models\Barbershop\Ticket;
use App\Modules\Administrator\Barbershop\Http\Resources\Service\ServiceSelectItemByInfrastructureResource;
use App\Modules\Administrator\Treasury\Repositories\Actions\GenerateIncomePdfAction;
use App\Modules\BarberPanel\Shared\BarberContext;
use App\Modules\BarberPanel\Ticket\Http\Requests\TicketRequest;
use App\Modules\BarberPanel\Ticket\Http\Resources\TicketDataTableItemResource;
use App\Modules\BarberPanel\Ticket\Http\Resources\TicketResource;
use App\Modules\BarberPanel\Ticket\Http\Resources\WaitingQueueTicketResource;
use App\Modules\BarberPanel\Ticket\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TicketController
{
    public function __construct(
        private readonly TicketService $service,
        private readonly GenerateIncomePdfAction $generateIncomePdfAction,
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

    public function generatePdf(int $incomeId): Response
    {
        $barberId = BarberContext::barberId();

        // Verificar que el income pertenece a un ticket de este barbero
        $income = Income::where('id', $incomeId)
            ->where('transactionable_type', 'barbershop_tickets')
            ->firstOrFail();

        Ticket::where('id', $income->transactionable_id)
            ->where('profile_barber_id', $barberId)
            ->firstOrFail();

        return ($this->generateIncomePdfAction)->execute($incomeId);
    }
}
