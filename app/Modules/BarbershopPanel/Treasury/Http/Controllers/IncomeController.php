<?php

namespace App\Modules\BarbershopPanel\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\BarbershopPanel\Treasury\Services\IncomeService;
use App\Modules\Administrator\Treasury\Http\Resources\Income\IncomeDataTableItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\Income\IncomeDetailResource;

class IncomeController
{
    public function __construct(
        private readonly IncomeService $incomeService,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->incomeService->dataTable($request);
        $items['data'] = IncomeDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(int $id): JsonResponse
    {
        $income = $this->incomeService->getById($id);
        $income = new IncomeDetailResource($income);
        return ApiResponse::success($income);
    }

    public function annul(int $id): JsonResponse
    {
        $this->incomeService->annul($id);
        return ApiResponse::success(null, 'Ingreso anulado correctamente');
    }

    public function generatePdf(int $id)
    {
        return $this->incomeService->generatePdf($id);
    }
}
