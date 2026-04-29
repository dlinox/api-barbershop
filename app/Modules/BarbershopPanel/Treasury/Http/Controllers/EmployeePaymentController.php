<?php

namespace App\Modules\BarbershopPanel\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Common\Http\Context\AdminContext;
use App\Modules\BarbershopPanel\Treasury\Services\EmployeePaymentService;
use App\Modules\Administrator\Treasury\Http\Requests\EmployeePayment\EmployeePaymentRequest;
use App\Modules\Administrator\Treasury\Http\Resources\EmployeePayment\EmployeePaymentDataTableItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\Barber\BarberPaymentSummaryItemResource;
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

    public function barberDataTable(Request $request)
    {
        $items = $this->employeePaymentService->barberDataTable($request);
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

    public function saveBarberPayment(EmployeePaymentRequest $request)
    {
        $data = $request->validated();
        $data['employee_type'] = 'profile_barbers';
        $data['infrastructure_id'] = AdminContext::infrastructureId();
        $this->employeePaymentService->save($data);
        return ApiResponse::success(null, 'Pago registrado correctamente');
    }

    public function workerPaymentSummary(Request $request)
    {
        $items = $this->employeePaymentService->workerPaymentSummary($request);
        $items['data'] = WorkerPaymentSummaryItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function barberPaymentSummary(Request $request)
    {
        $items = $this->employeePaymentService->barberPaymentSummary($request);
        $items['data'] = BarberPaymentSummaryItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function workerPaymentCalculation(Request $request, int $workerId)
    {
        $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $data = $this->employeePaymentService->workerPaymentCalculation(
            $workerId,
            $request->period_start,
            $request->period_end,
        );

        return ApiResponse::success($data);
    }

    public function barberPaymentCalculation(Request $request, int $barberId)
    {
        $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $data = $this->employeePaymentService->barberPaymentCalculation(
            $barberId,
            $request->period_start,
            $request->period_end,
        );

        return ApiResponse::success($data);
    }

    public function delete(int $id)
    {
        $this->employeePaymentService->delete($id);
        return ApiResponse::success(null, 'Pago eliminado correctamente');
    }
}
