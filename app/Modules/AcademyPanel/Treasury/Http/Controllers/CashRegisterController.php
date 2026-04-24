<?php

namespace App\Modules\AcademyPanel\Treasury\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Http\Resources\CashRegister\CashRegisterSelectItemResource;
use App\Modules\Administrator\Treasury\Services\CashRegisterService;
use Illuminate\Http\JsonResponse;

class CashRegisterController
{
    public function __construct(
        private readonly CashRegisterService $cashRegisterService,
    ) {}

    public function selectItems(int $infrastructureId): JsonResponse
    {
        $items = $this->cashRegisterService->getActiveCashRegisters($infrastructureId);
        return ApiResponse::success(CashRegisterSelectItemResource::collection($items));
    }
}
