<?php

namespace App\Modules\AcademyPanel\Treasury\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Http\Requests\Expense\ExpenseRequest;
use App\Modules\Administrator\Treasury\Http\Resources\Expense\ExpenseItemResource;
use App\Modules\Administrator\Treasury\Services\ExpenseService;
use Illuminate\Http\JsonResponse;

class ExpenseController
{
    public function __construct(
        private readonly ExpenseService $service,
    ) {}

    public function list(int $cashSessionId): JsonResponse
    {
        $items = $this->service->listByCashSession($cashSessionId);
        return ApiResponse::success(ExpenseItemResource::collection($items));
    }

    public function save(ExpenseRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->service->saveCashExpense($data);
        return ApiResponse::success($data, 'Gasto guardado correctamente');
    }

    public function delete(int $id): JsonResponse
    {
        $this->service->delete($id);
        return ApiResponse::success(null, 'Gasto eliminado correctamente');
    }
}
