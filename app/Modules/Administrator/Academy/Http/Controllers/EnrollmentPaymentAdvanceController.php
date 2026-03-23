<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Academy\Services\EnrollmentPaymentAdvanceService;
use App\Modules\Administrator\Academy\Http\Requests\EnrollmentPaymentAdvance\EnrollmentPaymentAdvanceRequest;
use App\Modules\Administrator\Academy\Http\Resources\EnrollmentPaymentAdvance\EnrollmentPaymentAdvanceResource;

class EnrollmentPaymentAdvanceController
{
    public function __construct(
        private EnrollmentPaymentAdvanceService $service
    ) {}

    public function getAvailableByStudentId(int $studentId)
    {
        $items = $this->service->getAvailableByStudentId($studentId);
        $items = EnrollmentPaymentAdvanceResource::collection($items);
        return ApiResponse::success($items);
    }

    public function save(EnrollmentPaymentAdvanceRequest $request)
    {
        $data = $request->validated();
        $this->service->save($data);
        return ApiResponse::success(null, 'Adelanto guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->service->delete($id);
        return ApiResponse::success(null, 'Adelanto eliminado correctamente');
    }
}
