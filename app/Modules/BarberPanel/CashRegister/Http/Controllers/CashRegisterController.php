<?php

namespace App\Modules\BarberPanel\CashRegister\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\BarberPanel\CashRegister\Http\Requests\OpenSessionRequest;
use App\Modules\BarberPanel\CashRegister\Http\Resources\CashRegisterItemResource;
use App\Modules\BarberPanel\CashRegister\Http\Resources\CashSessionStatusResource;
use App\Modules\BarberPanel\CashRegister\Services\CashRegisterService;
use Illuminate\Http\JsonResponse;

class CashRegisterController
{
    public function __construct(
        private readonly CashRegisterService $service,
    ) {}

    public function items(): JsonResponse
    {
        $items = $this->service->items();
        return ApiResponse::success(CashRegisterItemResource::collection($items));
    }

    public function sessionStatus(int $cashRegisterId): JsonResponse
    {
        $status = $this->service->sessionStatus($cashRegisterId);
        return ApiResponse::success(new CashSessionStatusResource($status));
    }

    public function openSession(OpenSessionRequest $request): JsonResponse
    {
        $this->service->openSession(
            cashRegisterId: $request->cash_register_id,
            openingAmount: (float) $request->opening_amount,
            userId: $request->user()->id,
            notes: $request->notes,
        );

        return ApiResponse::created(null, 'Caja abierta correctamente.');
    }
}
