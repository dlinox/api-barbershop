<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use Illuminate\Http\Request;

use App\Common\Http\Responses\ApiResponse;

use App\Modules\Administrator\Academy\Services\EnrollmentPaymentService;

use App\Modules\Administrator\Academy\Http\Requests\EnrollmentPayment\EnrollmentPaymentRequest;
use App\Modules\Administrator\Academy\Http\Resources\EnrollmentPayment\EnrollmentPaymentDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\EnrollmentPayment\EnrollmentPaymentHistoryResource;

class EnrollmentPaymentController
{
    public function __construct(
        private EnrollmentPaymentService $enrollmentPaymentService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->enrollmentPaymentService->dataTable($request);
        $items['data'] = EnrollmentPaymentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(EnrollmentPaymentRequest $request)
    {
        $data = $request->validated();
        $this->enrollmentPaymentService->save($data);
        return ApiResponse::success(null, 'Pago guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->enrollmentPaymentService->delete($id);
        return ApiResponse::success(null, 'Pago eliminado correctamente');
    }

    public function historyByEnrollmentId(int $enrollmentId)
    {
        $items = $this->enrollmentPaymentService->historyByEnrollmentId($enrollmentId);
        $items = EnrollmentPaymentHistoryResource::collection($items);
        return ApiResponse::success($items);
    }
}
