<?php

namespace App\Modules\Administrator\Report\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Report\Services\ReportAcademyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportAcademyController
{
    public function __construct(
        private readonly ReportAcademyService $service,
    ) {}

    public function summary(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->summary($request));
    }

    public function enrollmentTrend(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->enrollmentTrend($request));
    }

    public function revenueByType(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->revenueByType($request));
    }

    public function groupOccupancy(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->groupOccupancy($request));
    }

    public function groupDetail(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->groupDetail($request));
    }

    public function dailyIncome(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->dailyIncome($request));
    }
}
