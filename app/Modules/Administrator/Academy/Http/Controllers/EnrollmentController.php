<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Academy\Services\EnrollmentService;
use App\Modules\Administrator\Academy\Http\Requests\Enrollment\EnrollmentRequest;
use App\Modules\Administrator\Academy\Http\Resources\Enrollment\EnrollmentDataTableItemResource;
use Illuminate\Http\Request;

class EnrollmentController
{
    public function __construct(
        private EnrollmentService $enrollmentService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->enrollmentService->dataTable($request);
        $items['data'] = EnrollmentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(EnrollmentRequest $request)
    {
        $data = $request->validated();
        $this->enrollmentService->save($data);
        return ApiResponse::success('Registro guardado correctamente');
    }
}
