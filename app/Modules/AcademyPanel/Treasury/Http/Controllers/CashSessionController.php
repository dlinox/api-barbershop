<?php

namespace App\Modules\AcademyPanel\Treasury\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Http\Requests\CashSession\CloseSessionRequest;
use App\Modules\Administrator\Treasury\Http\Requests\CashSession\OpenSessionRequest;
use App\Modules\Administrator\Treasury\Http\Resources\CashSession\CashSessionDataTableItemResource;
use App\Modules\Administrator\Treasury\Services\CashSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CashSessionController
{
    public function __construct(
        private readonly CashSessionService $service,
    ) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->service->dataTable($request);
        $items['data'] = CashSessionDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function openSession(OpenSessionRequest $request): JsonResponse
    {
        $session = $this->service->openSession(
            cashRegisterId: $request->cash_register_id,
            openingAmount: (float) $request->opening_amount,
            userId: $request->user()->id,
            notes: $request->notes,
        );

        return ApiResponse::created(
            new CashSessionDataTableItemResource($session->load(['openedByUser', 'closedByUser'])),
            'Caja abierta correctamente.',
        );
    }

    public function closeSession(CloseSessionRequest $request): JsonResponse
    {
        $session = $this->service->closeSession(
            cashSessionId: $request->cash_session_id,
            actualClosingAmount: (float) $request->actual_closing_amount,
            userId: $request->user()->id,
            notes: $request->notes,
        );

        return ApiResponse::success(
            new CashSessionDataTableItemResource($session),
            'Caja cerrada correctamente.',
        );
    }

    public function closingPdf(int $cashSessionId): Response
    {
        $pdfContent = $this->service->generateClosingPdf($cashSessionId);

        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="cierre-de-caja.pdf"',
        ]);
    }

    public function currentSession(int $cashRegisterId): JsonResponse
    {
        $session = $this->service->getOpenSession($cashRegisterId);

        return ApiResponse::success(
            $session ? new CashSessionDataTableItemResource($session->load(['openedByUser', 'closedByUser'])) : null,
        );
    }

    public function currentSessionId(int $cashRegisterId): JsonResponse
    {
        $session = $this->service->getOpenSession($cashRegisterId);
        return ApiResponse::success($session?->id);
    }
}
