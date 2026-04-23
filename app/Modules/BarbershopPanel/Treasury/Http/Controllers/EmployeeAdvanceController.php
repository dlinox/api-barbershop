<?php

namespace App\Modules\BarbershopPanel\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Common\Http\Context\AdminContext;
use App\Modules\BarbershopPanel\Treasury\Services\EmployeeAdvanceService;
use App\Modules\Administrator\Treasury\Http\Requests\EmployeeAdvance\EmployeeAdvanceRequest;
use App\Modules\Administrator\Treasury\Http\Resources\EmployeeAdvance\EmployeeAdvanceDataTableItemResource;

class EmployeeAdvanceController
{
    public function __construct(
        private readonly EmployeeAdvanceService $employeeAdvanceService,
    ) {}

    public function workerDataTable(Request $request)
    {
        $items = $this->employeeAdvanceService->workerDataTable($request);
        $items['data'] = EmployeeAdvanceDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function barberDataTable(Request $request)
    {
        $items = $this->employeeAdvanceService->barberDataTable($request);
        $items['data'] = EmployeeAdvanceDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function saveWorkerAdvance(EmployeeAdvanceRequest $request)
    {
        $data = $request->validated();
        $data['employee_type'] = 'profile_workers';
        $data['infrastructure_id'] = AdminContext::infrastructureId();
        $this->employeeAdvanceService->save($data);
        return ApiResponse::success(null, 'Adelanto guardado correctamente');
    }

    public function saveBarberAdvance(EmployeeAdvanceRequest $request)
    {
        $data = $request->validated();
        $data['employee_type'] = 'profile_barbers';
        $data['infrastructure_id'] = AdminContext::infrastructureId();
        $this->employeeAdvanceService->save($data);
        return ApiResponse::success(null, 'Adelanto guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->employeeAdvanceService->delete($id);
        return ApiResponse::success(null, 'Adelanto eliminado correctamente');
    }
}
