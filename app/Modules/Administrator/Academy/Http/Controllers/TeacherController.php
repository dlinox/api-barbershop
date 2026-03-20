<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\Administrator\Academy\Services\TeacherService;
use App\Modules\Administrator\Academy\Http\Requests\Teacher\TeacherRequest;
use App\Modules\Administrator\Academy\Http\Resources\Teacher\TeacherDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Teacher\TeacherSelectItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\Teacher\TeacherPaymentSummaryItemResource;

class TeacherController
{
    public function __construct(
        private TeacherService $teacherService
    ) {}

    public function dataTable(Request $request)
    {
        $item = $this->teacherService->dataTable($request);
        $item['data'] = TeacherDataTableItemResource::collection($item['data']);
        return ApiResponse::success($item);
    }

    public function paymentSummaryDataTable(Request $request)
    {
        $item = $this->teacherService->paymentSummaryDataTable($request);
        $item['data'] = TeacherPaymentSummaryItemResource::collection($item['data']);
        return ApiResponse::success($item);
    }

    public function save(TeacherRequest $request)
    {
        $data = $request->validated();
        $this->teacherService->save($data);
        return ApiResponse::success(null, 'Docente creado correctamente');
    }

    public function selectAsyncItems(Request $request)
    {
        $item = $this->teacherService->selectAsyncItems($request);
        $item = TeacherSelectItemResource::collection($item);
        return ApiResponse::success($item);
    }

    public function paymentCalculation(Request $request, int $teacherId)
    {
        $data = $this->teacherService->paymentCalculation(
            $teacherId,
            $request->input('period_start'),
            $request->input('period_end'),
        );
        return ApiResponse::success($data);
    }
}
