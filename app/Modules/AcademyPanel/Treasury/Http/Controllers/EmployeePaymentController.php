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
use App\Modules\Administrator\Treasury\Repositories\Actions\GenerateWorkerPaymentPdfAction;
use App\Modules\Administrator\Treasury\Repositories\Actions\GenerateTeacherPaymentPdfAction;

class EmployeePaymentController
{
    public function __construct(
        private readonly EmployeePaymentService $employeePaymentService,
        private readonly GenerateWorkerPaymentPdfAction $generateWorkerPaymentPdfAction,
        private readonly GenerateTeacherPaymentPdfAction $generateTeacherPaymentPdfAction,
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

        $data = $this->employeePaymentService->teacherPaymentCalculation(
            $teacherId,
            $request->input('period_start'),
            $request->input('period_end'),
        );
        return ApiResponse::success($data);
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
        $payment = $this->employeePaymentService->save($data);
        return ApiResponse::success(['paymentId' => $payment->id], 'Pago registrado correctamente');
    }

    public function generateWorkerPaymentPdf(int $id)
    {
        return $this->generateWorkerPaymentPdfAction->execute($id);
    }

    public function saveTeacherPayment(EmployeePaymentRequest $request)
    {
        $data = $request->validated();
        $data['employee_type'] = 'profile_teachers';
        $data['infrastructure_id'] = AdminContext::infrastructureId();
        $payment = $this->employeePaymentService->save($data);
        return ApiResponse::success(['paymentId' => $payment->id], 'Pago registrado correctamente');
    }

    public function generateTeacherPaymentPdf(int $id)
    {
        return $this->generateTeacherPaymentPdfAction->execute($id);
    }

    public function delete(int $id)
    {
        $this->employeePaymentService->delete($id);
        return ApiResponse::success(null, 'Pago eliminado correctamente');
    }
}
