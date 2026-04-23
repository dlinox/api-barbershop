<?php

namespace App\Modules\AcademyPanel\Academy\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\AcademyPanel\Academy\Services\AttendanceService;
use App\Modules\Administrator\Academy\Http\Requests\Attendance\StartAttendanceRequest;
use App\Modules\Administrator\Academy\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Modules\Administrator\Academy\Http\Requests\Attendance\RegisterAttendanceRequest;
use App\Modules\Administrator\Academy\Http\Resources\Attendance\GroupAttendanceItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Attendance\AttendanceDataTableResource;

class AttendanceController
{
    public function __construct(private AttendanceService $attendanceService) {}

    public function dataTable(Request $request)
    {
        $items = $this->attendanceService->dataTable($request);
        $items['data'] = AttendanceDataTableResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getGroupsByAttendance()
    {
        $groups = $this->attendanceService->getGroupsByAttendance();
        return ApiResponse::success(GroupAttendanceItemResource::collection($groups));
    }

    public function startAttendanceDeadline(StartAttendanceRequest $request)
    {
        $this->attendanceService->startAttendanceDeadline($request->validated());
        return ApiResponse::success(null, 'Asistencia iniciada correctamente');
    }

    public function updateAttendanceDeadline(UpdateAttendanceRequest $request)
    {
        $this->attendanceService->updateAttendanceDeadline($request->validated());
        return ApiResponse::success(null, 'Asistencia finalizada correctamente');
    }

    public function registerAttendanceByDocument(RegisterAttendanceRequest $request)
    {
        $this->attendanceService->registerAttendanceByDocument($request->validated());
        return ApiResponse::success(null, 'Asistencia registrada correctamente');
    }

    public function registerAttendanceByCode(RegisterAttendanceRequest $request)
    {
        $this->attendanceService->registerAttendanceByCode($request->validated());
        return ApiResponse::success(null, 'Asistencia registrada correctamente');
    }
}