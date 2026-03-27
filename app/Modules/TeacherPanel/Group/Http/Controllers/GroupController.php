<?php

namespace App\Modules\TeacherPanel\Group\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\TeacherPanel\Group\Http\Resources\AttendanceItemResource;
use App\Modules\TeacherPanel\Group\Http\Resources\GroupDataTableItemResource;
use App\Modules\TeacherPanel\Group\Http\Resources\GroupStudentResource;
use App\Modules\TeacherPanel\Group\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController
{
    public function __construct(
        private readonly GroupService $service,
    ) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->service->dataTable($request);
        $items['data'] = GroupDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function students(int $groupId): JsonResponse
    {
        $students = $this->service->students($groupId);
        return ApiResponse::success(GroupStudentResource::collection($students));
    }

    public function attendanceDeadlineStatus(int $groupId, Request $request): JsonResponse
    {
        $date = $request->query('date', date('Y-m-d'));
        $result = $this->service->attendanceDeadlineStatus($groupId, $date);
        return ApiResponse::success($result);
    }

    public function startAttendanceDeadline(int $groupId, Request $request): JsonResponse
    {
        $date = $request->input('date', date('Y-m-d'));
        $this->service->startAttendanceDeadline($groupId, $date);
        return ApiResponse::success(null, 'Asistencia iniciada correctamente');
    }

    public function attendances(int $groupId, Request $request): JsonResponse
    {
        $date = $request->query('date', date('Y-m-d'));
        $attendances = $this->service->attendances($groupId, $date);
        return ApiResponse::success(AttendanceItemResource::collection($attendances));
    }

    public function updateAttendanceStatus(int $groupId, Request $request): JsonResponse
    {
        $this->service->updateAttendanceStatus(
            $groupId,
            $request->input('enrollmentId'),
            $request->input('date', date('Y-m-d')),
            $request->input('status'),
        );
        return ApiResponse::success(null, 'Asistencia actualizada');
    }
}
