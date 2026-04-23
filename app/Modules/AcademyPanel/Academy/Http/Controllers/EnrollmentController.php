<?php

namespace App\Modules\AcademyPanel\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\AcademyPanel\Academy\Services\EnrollmentService;
use App\Modules\Administrator\Academy\Http\Requests\Enrollment\EnrollmentWithIncomeRequest;
use App\Modules\Administrator\Academy\Http\Requests\Enrollment\EnrollmentRequest;
use App\Modules\Administrator\Academy\Http\Requests\Enrollment\EnrollmentUpdateRequest;
use App\Modules\Administrator\Academy\Http\Requests\Enrollment\EnrollmentRegisterPaymentRequest;
use App\Modules\Administrator\Academy\Http\Resources\Enrollment\EnrollmentDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Enrollment\EnrollmentDetailResource;
use Illuminate\Http\Request;

class EnrollmentController
{
    public function __construct(private EnrollmentService $enrollmentService) {}

    public function dataTable(Request $request)
    {
        $items = $this->enrollmentService->dataTable($request);
        $items['data'] = EnrollmentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(EnrollmentWithIncomeRequest $request)
    {
        $result = $this->enrollmentService->save($request->validated());
        return ApiResponse::success($result, 'Matrícula guardada correctamente');
    }

    public function saveWithoutPayment(EnrollmentRequest $request)
    {
        $this->enrollmentService->saveWithoutPayment($request->validated());
        return ApiResponse::success(null, 'Matrícula registrada correctamente');
    }

    public function update(EnrollmentUpdateRequest $request)
    {
        $this->enrollmentService->update($request->validated());
        return ApiResponse::success(null, 'Matrícula actualizada correctamente');
    }

    public function registerPayment(EnrollmentRegisterPaymentRequest $request)
    {
        $this->enrollmentService->registerPayment($request->validated());
        return ApiResponse::success(null, 'Pago registrado correctamente');
    }

    public function getEnrollment(int $id)
    {
        $enrollment = $this->enrollmentService->getEnrollment($id);
        return ApiResponse::success(new EnrollmentDataTableItemResource($enrollment));
    }

    public function generatePdf(int $id)
    {
        return $this->enrollmentService->generatePdf($id);
    }

    public function detail(int $id)
    {
        $enrollment = $this->enrollmentService->detail($id);
        return ApiResponse::success(new EnrollmentDetailResource($enrollment));
    }
}