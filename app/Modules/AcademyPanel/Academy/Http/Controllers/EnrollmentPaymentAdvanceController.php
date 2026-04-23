<?php

namespace App\Modules\AcademyPanel\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\AcademyPanel\Academy\Services\EnrollmentPaymentAdvanceService;
use App\Modules\Administrator\Academy\Http\Requests\EnrollmentPaymentAdvance\EnrollmentPaymentAdvanceRequest;
use App\Modules\Administrator\Academy\Http\Resources\EnrollmentPaymentAdvance\EnrollmentPaymentAdvanceItemResource;

class EnrollmentPaymentAdvanceController
{
    public function __construct(private EnrollmentPaymentAdvanceService $advanceService) {}

    public function getAvailableByStudentId(int $studentId)
    {
        $items = $this->advanceService->getAvailableByStudentId($studentId);
        return ApiResponse::success(EnrollmentPaymentAdvanceItemResource::collection($items));
    }

    public function save(EnrollmentPaymentAdvanceRequest $request)
    {
        $this->advanceService->save($request->validated());
        return ApiResponse::success(null, 'Adelanto guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->advanceService->delete($id);
        return ApiResponse::success(null, 'Adelanto eliminado correctamente');
    }
}