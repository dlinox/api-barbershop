<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;

use App\Modules\Administrator\Academy\Services\AttendanceService;

use App\Modules\Administrator\Academy\Http\Requests\Attendance\AttendanceRequest;
use App\Modules\Administrator\Academy\Http\Requests\Attendance\StartAttendanceRequest;
use App\Modules\Administrator\Academy\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Modules\Administrator\Academy\Http\Requests\Attendance\RegisterAttendanceRequest;

use App\Modules\Administrator\Academy\Http\Resources\Attendance\GroupAttendanceItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Attendance\AttendanceDataTableResource;

class AttendanceController
{
    public function __construct(
        private AttendanceService $attendanceService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->attendanceService->dataTable($request);
        $items['data'] = AttendanceDataTableResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(AttendanceRequest $request)
    {
        $data = $request->validated();
        $this->attendanceService->save($data);
        return ApiResponse::success(null, 'Asistencia guardada correctamente');
    }

    public function getGroupsByAttendance()
    {
        $groups = $this->attendanceService->getGroupsByAttendance();
        $groups = GroupAttendanceItemResource::collection($groups);
        return ApiResponse::success($groups);
    }

    public function startAttendanceDeadline(StartAttendanceRequest $request)
    {
        $data = $request->validated();
        $this->attendanceService->startAttendanceDeadline($data);
        return ApiResponse::success(null, 'Asistencia iniciada correctamente');
    }

    public function updateAttendanceDeadline(UpdateAttendanceRequest $request)
    {
        $data = $request->validated();
        $this->attendanceService->updateAttendanceDeadline($data);
        return ApiResponse::success(null, 'Asistencia finalizada correctamente');
    }


    public function registerAttendanceByDocument(RegisterAttendanceRequest $request)
    {
        $data = $request->validated();
        $this->attendanceService->registerAttendanceByDocument($data);
        return ApiResponse::success(null, 'Asistencia registrada correctamente');
    }

    public function registerAttendanceByCode(RegisterAttendanceRequest $request)
    {
        $data = $request->validated();
        $this->attendanceService->registerAttendanceByCode($data);
        return ApiResponse::success(null, 'Asistencia registrada correctamente');
    }

    public function delete(int $id)
    {
        $this->attendanceService->delete($id);
        return ApiResponse::success(null, 'Asistencia eliminada correctamente');
    }
}
