<?php

namespace App\Modules\TeacherPanel\Dashboard\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\TeacherPanel\Dashboard\Http\Resources\DashboardSummaryResource;
use App\Modules\TeacherPanel\Dashboard\Http\Resources\RecentAttendanceResource;
use App\Modules\TeacherPanel\Dashboard\Http\Resources\UpcomingClassResource;
use App\Modules\TeacherPanel\Dashboard\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController
{
    public function __construct(
        private readonly DashboardService $service,
    ) {}

    public function summary(): JsonResponse
    {
        return ApiResponse::success(new DashboardSummaryResource($this->service->summary()));
    }

    public function upcomingClasses(): JsonResponse
    {
        return ApiResponse::success(UpcomingClassResource::collection($this->service->upcomingClasses()));
    }

    public function recentAttendance(): JsonResponse
    {
        return ApiResponse::success(RecentAttendanceResource::collection($this->service->recentAttendance()));
    }
}
