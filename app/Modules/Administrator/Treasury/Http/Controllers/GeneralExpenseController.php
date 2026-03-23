<?php

namespace App\Modules\Administrator\Treasury\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Http\Requests\Expense\GeneralExpenseRequest;
use App\Modules\Administrator\Treasury\Http\Resources\Expense\ExpenseDataTableItemResource;
use App\Modules\Administrator\Treasury\Services\ExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeneralExpenseController
{
    public function __construct(
        private readonly ExpenseService $service,
    ) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->service->dataTable($request);
        $items['data'] = ExpenseDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(GeneralExpenseRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->service->saveGeneralExpense($data);
        return ApiResponse::success($data, 'Gasto guardado correctamente');
    }

    public function getById(int $id): JsonResponse
    {
        $item = $this->service->getById($id);
        return ApiResponse::success(new ExpenseDataTableItemResource($item));
    }

    public function cancel(int $id): JsonResponse
    {
        $this->service->cancel($id);
        return ApiResponse::success(null, 'Gasto anulado correctamente');
    }
}
