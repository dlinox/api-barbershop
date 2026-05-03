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
use App\Modules\Administrator\Treasury\Repositories\Actions\GenerateWorkerPaymentPdfAction;
use App\Modules\BarbershopPanel\Treasury\Repositories\Actions\GenerateBarberPaymentPdfAction;

class EmployeePaymentController
{
    public function __construct(
        private readonly EmployeePaymentService $employeePaymentService,
        private readonly GenerateWorkerPaymentPdfAction $generateWorkerPaymentPdfAction,
        private readonly GenerateBarberPaymentPdfAction $generateBarberPaymentPdfAction,
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
        $payment = $this->employeePaymentService->save($data);
        return ApiResponse::success(['paymentId' => $payment->id], 'Pago registrado correctamente');
    }

    public function generateWorkerPaymentPdf(int $id)
    {
        return $this->generateWorkerPaymentPdfAction->execute($id);
    }

    public function generateBarberPaymentPdf(int $id)
    {
        return $this->generateBarberPaymentPdfAction->execute($id);
    }

    public function saveBarberPayment(EmployeePaymentRequest $request)
    {
        $data = $request->validated();
        $data['employee_type'] = 'profile_barbers';
        $data['infrastructure_id'] = AdminContext::infrastructureId();
        $payment = $this->employeePaymentService->save($data);
        return ApiResponse::success(['paymentId' => $payment->id], 'Pago registrado correctamente');
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
        $request->validate(
            [
                'period_start' => 'required|date',
                'period_end'   => 'required|date|after_or_equal:period_start',
            ],
            [
                'period_start.required'     => 'La fecha de inicio es requerida',
                'period_start.date'         => 'La fecha de inicio debe ser una fecha válida',
                'period_end.required'       => 'La fecha de fin es requerida',
                'period_end.date'           => 'La fecha de fin debe ser una fecha válida',
                'period_end.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
            ]
        );

        $data = $this->employeePaymentService->workerPaymentCalculation(
            $workerId,
            $request->period_start,
            $request->period_end,
        );

        return ApiResponse::success($data);
    }

    public function barberPaymentCalculation(Request $request, int $barberId)
    {
        $request->validate(
            [
                'period_start' => 'required|date',
                'period_end'   => 'required|date|after_or_equal:period_start',
            ],
            [
                'period_start.required'     => 'La fecha de inicio es requerida',
                'period_start.date'         => 'La fecha de inicio debe ser una fecha válida',
                'period_end.required'       => 'La fecha de fin es requerida',
                'period_end.date'           => 'La fecha de fin debe ser una fecha válida',
                'period_end.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
            ]
        );

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
