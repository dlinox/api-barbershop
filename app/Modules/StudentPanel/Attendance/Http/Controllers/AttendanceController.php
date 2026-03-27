<?php

namespace App\Modules\StudentPanel\Attendance\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\StudentPanel\Attendance\Http\Resources\AttendanceDataTableItemResource;
use App\Modules\StudentPanel\Attendance\Services\AttendanceService;
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
