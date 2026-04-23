<?php

namespace App\Modules\AcademyPanel\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Common\Http\Context\AdminContext;
use App\Modules\AcademyPanel\Treasury\Services\EmployeePaymentService;
use App\Modules\Administrator\Treasury\Http\Requests\EmployeePayment\EmployeePaymentRequest;
use App\Modules\Administrator\Treasury\Http\Resources\EmployeePayment\EmployeePaymentDataTableItemResource;

class EmployeePaymentController
{
    public function __construct(
        private readonly EmployeePaymentService $employeePaymentService,
    ) {}

    public function workerDataTable(Request $request)
    {
        $items = $this->employeePaymentService->workerDataTable($request);
        $items['data'] = EmployeePaymentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function teacherDataTable(Request $request)
    {
        $items = $this->employeePaymentService->teacherDataTable($request);
        $items['data'] = EmployeePaymentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function saveWorkerPayment(EmployeePaymentRequest $request)
    {
        $data = $request->validated();
        $data['employee_type'] = 'profile_workers';
        $data['infrastructure_id'] = AdminContext::infrastructureId();
        $this->employeePaymentService->save($data);
        return ApiResponse::success(null, 'Pago registrado correctamente');
    }

    public function saveTeacherPayment(EmployeePaymentRequest $request)
    {
        $data = $request->validated();
        $data['employee_type'] = 'profile_teachers';
        $data['infrastructure_id'] = AdminContext::infrastructureId();
        $this->employeePaymentService->save($data);
        return ApiResponse::success(null, 'Pago registrado correctamente');
    }

    public function delete(int $id)
    {
        $this->employeePaymentService->delete($id);
        return ApiResponse::success(null, 'Pago eliminado correctamente');
    }
}
