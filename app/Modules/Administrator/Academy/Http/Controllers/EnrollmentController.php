<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Academy\Services\EnrollmentService;
use App\Modules\Administrator\Academy\Http\Requests\Enrollment\EnrollmentWithIncomeRequest;
use App\Modules\Administrator\Academy\Http\Requests\Enrollment\EnrollmentUpdateRequest;
use App\Modules\Administrator\Academy\Http\Requests\Enrollment\EnrollmentRegisterPaymentRequest;
use App\Modules\Administrator\Academy\Http\Resources\Enrollment\EnrollmentDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Enrollment\EnrollmentDetailResource;
use Illuminate\Http\Request;

class EnrollmentController
{
    public function __construct(
        private EnrollmentService $enrollmentService,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->enrollmentService->dataTable($request);
        $items['data'] = EnrollmentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(EnrollmentWithIncomeRequest $request)
    {
        $req = $request->validated();

        $this->enrollmentService->save($req);

        return ApiResponse::success(null, 'Registro guardado correctamente');
    }

    public function update(EnrollmentUpdateRequest $request)
    {
        $data = $request->validated();

        $this->enrollmentService->update($data);

        return ApiResponse::success(null, 'Registro actualizado correctamente');
    }

    public function registerPayment(EnrollmentRegisterPaymentRequest $request)
    {
        $data = $request->validated();
        $this->enrollmentService->registerPayment($data);
        return ApiResponse::success(null, 'Registro guardado correctamente');
    }

    public function getEnrollment($id)
    {
        $enrollment = $this->enrollmentService->getEnrollment($id);
        $enrollment = new EnrollmentDataTableItemResource($enrollment);
        return ApiResponse::success($enrollment);
    }

    public function generatePdf($id)
    {
        return $this->enrollmentService->generatePdf($id);
    }

    public function detail($id)
    {
        $enrollment = $this->enrollmentService->getDetail($id);
        return ApiResponse::success(new EnrollmentDetailResource($enrollment));
    }
}
