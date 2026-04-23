<?php

namespace App\Modules\AcademyPanel\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\AcademyPanel\Academy\Services\EnrollmentPaymentService;
use App\Modules\Administrator\Academy\Http\Resources\EnrollmentPayment\EnrollmentPaymentDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\EnrollmentPayment\EnrollmentPaymentHistoryItemResource;
use Illuminate\Http\Request;

class EnrollmentPaymentController
{
    public function __construct(private EnrollmentPaymentService $enrollmentPaymentService) {}

    public function dataTable(Request $request)
    {
        $items = $this->enrollmentPaymentService->dataTable($request);
        $items['data'] = EnrollmentPaymentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function historyByEnrollmentId(int $enrollmentId)
    {
        $items = $this->enrollmentPaymentService->historyByEnrollmentId($enrollmentId);
        return ApiResponse::success(EnrollmentPaymentHistoryItemResource::collection($items));
    }
}