<?php

namespace App\Modules\StudentPanel\Payment\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\StudentPanel\Payment\Http\Resources\PaymentDataTableItemResource;
use App\Modules\StudentPanel\Payment\Services\PaymentService;
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

    public function generatePdf(int $incomeId)
    {
        return $this->service->generatePdf($incomeId);
    }
}
