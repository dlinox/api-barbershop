<?php

namespace App\Modules\Administrator\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Services\EmployeePaymentService;
use App\Modules\Administrator\Treasury\Http\Requests\EmployeePayment\EmployeePaymentRequest;
use App\Modules\Administrator\Treasury\Http\Resources\EmployeePayment\EmployeePaymentDataTableItemResource;
use App\Modules\Administrator\Treasury\Repositories\Actions\GenerateTeacherPaymentPdfAction;
use App\Modules\BarbershopPanel\Treasury\Repositories\Actions\GenerateBarberPaymentPdfAction;

class EmployeePaymentController
{
    public function __construct(
        private EmployeePaymentService $employeePaymentService,
        private GenerateTeacherPaymentPdfAction $generateTeacherPaymentPdfAction,
        private GenerateBarberPaymentPdfAction $generateBarberPaymentPdfAction,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->employeePaymentService->dataTable($request);
        $items['data'] = EmployeePaymentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(EmployeePaymentRequest $request)
    {
        $data = $request->validated();
        $payment = $this->employeePaymentService->save($data);
        return ApiResponse::success(['paymentId' => $payment->id], 'Pago guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->employeePaymentService->delete($id);
        return ApiResponse::success(null, 'Pago eliminado correctamente');
    }

    public function generateTeacherPaymentPdf(int $id)
    {
        return $this->generateTeacherPaymentPdfAction->execute($id);
    }

    public function generateBarberPaymentPdf(int $id)
    {
        return $this->generateBarberPaymentPdfAction->execute($id);
    }
}
