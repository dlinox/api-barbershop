<?php

namespace App\Modules\Administrator\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Services\ExpenseTypeService;
use App\Modules\Administrator\Treasury\Http\Requests\ExpenseType\ExpenseTypeRequest;
use App\Modules\Administrator\Treasury\Http\Resources\ExpenseType\ExpenseTypeDataTableItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\ExpenseType\ExpenseTypeSelectItemResource;

class ExpenseTypeController
{
    public function __construct(
        private ExpenseTypeService $expenseTypeService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->expenseTypeService->dataTable($request);
        $items['data'] = ExpenseTypeDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(ExpenseTypeRequest $request)
    {
        $data = $request->validated();
        $this->expenseTypeService->save($data);
        return ApiResponse::success($data, 'Tipo de gasto guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->expenseTypeService->delete($id);
        return ApiResponse::success(null, 'Tipo de gasto eliminado correctamente');
    }

    public function selectItems()
    {
        $items = $this->expenseTypeService->getActiveExpenseTypes();
        return ApiResponse::success(ExpenseTypeSelectItemResource::collection($items));
    }
}
