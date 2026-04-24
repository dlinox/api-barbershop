<?php

namespace App\Modules\BarbershopPanel\Treasury\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Http\Resources\ExpenseType\ExpenseTypeSelectItemResource;
use App\Modules\Administrator\Treasury\Services\ExpenseTypeService;
use Illuminate\Http\JsonResponse;

class ExpenseTypeController
{
    public function __construct(
        private readonly ExpenseTypeService $expenseTypeService,
    ) {}

    public function selectItems(): JsonResponse
    {
        $items = $this->expenseTypeService->getActiveExpenseTypes();
        return ApiResponse::success(ExpenseTypeSelectItemResource::collection($items));
    }
}
