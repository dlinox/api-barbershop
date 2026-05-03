<?php

namespace App\Modules\Administrator\Treasury\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Services\IncomeService;
use App\Modules\Administrator\Treasury\Http\Resources\Income\IncomeDataTableItemResource;
use App\Modules\Administrator\Treasury\Http\Requests\Income\IncomeUpdateRequest;
use App\Modules\Administrator\Treasury\Http\Resources\Income\IncomeDetailResource;
use App\Modules\Administrator\Treasury\Http\Resources\Income\IncomeAuditResource;
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

    public function update(int $id, IncomeUpdateRequest $request): JsonResponse
    {
        $this->service->update($id, $request->validated());
        return ApiResponse::success(null, 'Ingreso actualizado correctamente');
    }

    public function generatePdf(int $id)
    {
        return $this->service->generatePdf($id);
    }

    public function audits(int $id): JsonResponse
    {
        $items = $this->service->audits($id);
        return ApiResponse::success(IncomeAuditResource::collection(collect($items)));
    }
}
