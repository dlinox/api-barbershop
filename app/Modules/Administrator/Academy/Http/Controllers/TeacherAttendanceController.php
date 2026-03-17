<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Academy\Services\TeacherAttendanceService;
use App\Modules\Administrator\Academy\Http\Resources\TeacherAttendance\TeacherAttendanceDataTableResource;

class TeacherAttendanceController
{
    public function __construct(
        private TeacherAttendanceService $teacherAttendanceService
    ) {}

    public function dataTable(Request $request, ?string $date = null)
    {
        $request->merge(['date' => $date]);
        $items = $this->teacherAttendanceService->dataTable($request);
        $items['data'] = TeacherAttendanceDataTableResource::collection($items['data']);
        return ApiResponse::success($items);
    }
}
