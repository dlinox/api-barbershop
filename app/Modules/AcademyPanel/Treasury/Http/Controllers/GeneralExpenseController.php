<?php

namespace App\Modules\AcademyPanel\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Common\Http\Context\AdminContext;
use App\Modules\AcademyPanel\Treasury\Services\ExpenseService;
use App\Modules\Administrator\Treasury\Http\Requests\Expense\GeneralExpenseRequest;
use App\Modules\Administrator\Treasury\Http\Resources\Expense\ExpenseDataTableItemResource;

class GeneralExpenseController
{
    public function __construct(
        private readonly ExpenseService $expenseService,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->expenseService->dataTable($request);
        $items['data'] = ExpenseDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(GeneralExpenseRequest $request)
    {
        $data = $request->validated();
        $data['infrastructure_id'] = AdminContext::infrastructureId();
        $this->expenseService->save($data);
        return ApiResponse::success(null, 'Gasto registrado correctamente');
    }

    public function getById(int $id)
    {
        $item = $this->expenseService->getById($id);
        return ApiResponse::success($item);
    }

    public function cancel(int $id)
    {
        $this->expenseService->cancel($id);
        return ApiResponse::success(null, 'Gasto cancelado correctamente');
    }
}
