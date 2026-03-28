<?php

namespace App\Modules\BarberPanel\Payment\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\BarberPanel\Payment\Http\Resources\PaymentDataTableItemResource;
use App\Modules\BarberPanel\Payment\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController
{
    public function __construct(
        private readonly PaymentService $service,
    ) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->service->dataTable($request);
        $items['data'] = PaymentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }
}
