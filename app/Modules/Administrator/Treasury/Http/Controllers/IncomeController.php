<?php

namespace App\Modules\Administrator\Treasury\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Services\IncomeService;
use App\Modules\Administrator\Treasury\Http\Resources\Income\IncomeDataTableItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\Income\IncomeDetailResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncomeController
{
    public function __construct(
        private readonly IncomeService $service,
    ) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->service->dataTable($request);
        $items['data'] = IncomeDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(int $id): JsonResponse
    {
        $income = $this->service->getById($id);
        $income = new IncomeDetailResource($income);
        return ApiResponse::success($income);
    }

    public function annul(int $id): JsonResponse
    {
        $this->service->annul($id);
        return ApiResponse::success(null, 'Ingreso anulado correctamente');
    }
}
