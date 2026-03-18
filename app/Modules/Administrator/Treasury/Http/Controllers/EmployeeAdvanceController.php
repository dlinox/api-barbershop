<?php

namespace App\Modules\Administrator\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Services\EmployeeAdvanceService;
use App\Modules\Administrator\Treasury\Http\Requests\EmployeeAdvance\EmployeeAdvanceRequest;
use App\Modules\Administrator\Treasury\Http\Resources\EmployeeAdvance\EmployeeAdvanceDataTableItemResource;

class EmployeeAdvanceController
{
    public function __construct(
        private EmployeeAdvanceService $employeeAdvanceService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->employeeAdvanceService->dataTable($request);
        $items['data'] = EmployeeAdvanceDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(EmployeeAdvanceRequest $request)
    {
        $data = $request->validated();
        $this->employeeAdvanceService->save($data);
        return ApiResponse::success($data, 'Adelanto guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->employeeAdvanceService->delete($id);
        return ApiResponse::success(null, 'Adelanto eliminado correctamente');
    }
}
