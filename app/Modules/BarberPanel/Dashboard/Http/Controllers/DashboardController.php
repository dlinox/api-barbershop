<?php

namespace App\Modules\BarberPanel\Dashboard\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\BarberPanel\Dashboard\Http\Resources\DashboardSummaryResource;
use App\Modules\BarberPanel\Dashboard\Http\Resources\RecentAttendanceResource;
use App\Modules\BarberPanel\Dashboard\Http\Resources\RecentTicketResource;
use App\Modules\BarberPanel\Dashboard\Services\DashboardService;
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

    public function recentTickets(): JsonResponse
    {
        return ApiResponse::success(RecentTicketResource::collection($this->service->recentTickets()));
    }

    public function recentAttendance(): JsonResponse
    {
        return ApiResponse::success(RecentAttendanceResource::collection($this->service->recentAttendance()));
    }
}
