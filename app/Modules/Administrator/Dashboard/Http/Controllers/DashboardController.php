<?php

namespace App\Modules\Administrator\Dashboard\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Dashboard\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController
{
    public function __construct(
        private readonly DashboardService $service,
    ) {}

    public function summary(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->summary($request));
    }

    public function revenueChart(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->revenueChart($request));
    }

    public function ticketsByBarber(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->ticketsByBarber($request));
    }

    public function topServices(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->topServices($request));
    }

    public function enrollmentsByGroup(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->enrollmentsByGroup($request));
    }

    public function attendanceOverview(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->attendanceOverview($request));
    }

    public function cashFlowChart(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->cashFlowChart($request));
    }

    public function lowStockAlerts(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->lowStockAlerts($request));
    }

    public function recentTickets(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->recentTickets($request));
    }

    public function reservationsByStatus(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->reservationsByStatus($request));
    }

    public function payrollSummary(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->payrollSummary($request));
    }
}
