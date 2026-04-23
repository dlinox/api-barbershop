<?php

namespace App\Modules\BarbershopPanel\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Common\Http\Context\AdminContext;
use App\Modules\BarbershopPanel\Treasury\Services\ExpenseService;
use App\Modules\Administrator\Treasury\Http\Requests\Expense\GeneralExpenseRequest;
use App\Modules\Administrator\Treasury\Http\Resources\Expense\ExpenseDataTableItemResource;

class GeneralExpenseController
{
    public function __construct(
        private readonly ExpenseService $generalExpenseService,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->generalExpenseService->dataTable($request);
        $items['data'] = ExpenseDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(GeneralExpenseRequest $request)
    {
        $data = $request->validated();
        $data['infrastructure_id'] = AdminContext::infrastructureId();
        $this->generalExpenseService->save($data);
        return ApiResponse::success(null, 'Gasto registrado correctamente');
    }

    public function getById(int $id)
    {
        $item = $this->generalExpenseService->getById($id);
        return ApiResponse::success($item);
    }

    public function cancel(int $id)
    {
        $this->generalExpenseService->cancel($id);
        return ApiResponse::success(null, 'Gasto cancelado correctamente');
    }
}
