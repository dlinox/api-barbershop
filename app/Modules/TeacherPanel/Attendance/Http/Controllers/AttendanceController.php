<?php

namespace App\Modules\TeacherPanel\Attendance\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\TeacherPanel\Attendance\Http\Resources\AttendanceDataTableItemResource;
use App\Modules\TeacherPanel\Attendance\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController
{
    public function __construct(
        private readonly AttendanceService $service,
    ) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->service->dataTable($request);
        $items['data'] = AttendanceDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }
}
