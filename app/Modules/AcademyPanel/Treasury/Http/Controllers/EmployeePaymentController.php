<?php

namespace App\Modules\AcademyPanel\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Common\Http\Context\AdminContext;
use App\Modules\AcademyPanel\Treasury\Services\EmployeePaymentService;
use App\Modules\Administrator\Treasury\Http\Requests\EmployeePayment\EmployeePaymentRequest;
use App\Modules\Administrator\Treasury\Http\Resources\EmployeePayment\EmployeePaymentDataTableItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\Teacher\TeacherPaymentSummaryItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\Worker\WorkerPaymentSummaryItemResource;

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

    public function teacherPaymentSummary(Request $request)
    {
        $items = $this->employeePaymentService->teacherPaymentSummary($request);
        $items['data'] = TeacherPaymentSummaryItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function workerPaymentSummary(Request $request)
    {
        $items = $this->employeePaymentService->workerPaymentSummary($request);
        $items['data'] = WorkerPaymentSummaryItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function teacherPaymentCalculation(Request $request, int $teacherId)
    {
        $request->validate([
            'period_start' => 'required|date',
            'period_end'   => 'required|date|after_or_equal:period_start',
        ]);

        $data = $this->employeePaymentService->teacherPaymentCalculation(
            $teacherId,
            $request->input('period_start'),
            $request->input('period_end'),
        );
        return ApiResponse::success($data);
    }

    public function workerPaymentCalculation(Request $request, int $workerId)
    {
        $request->validate([
            'period_start' => 'required|date',
            'period_end'   => 'required|date|after_or_equal:period_start',
        ]);

        $data = $this->employeePaymentService->workerPaymentCalculation(
            $workerId,
            $request->input('period_start'),
            $request->input('period_end'),
        );
        return ApiResponse::success($data);
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
