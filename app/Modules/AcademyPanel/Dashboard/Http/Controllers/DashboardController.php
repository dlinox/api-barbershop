<?php

namespace App\Modules\AcademyPanel\Dashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\AcademyPanel\Dashboard\Http\Resources\DashboardSummaryResource;
use App\Modules\AcademyPanel\Dashboard\Services\DashboardService;

class DashboardController
{
    public function __construct(
        private readonly DashboardService $service,
    ) {}

    public function summary(): JsonResponse
    {
        return ApiResponse::success(new DashboardSummaryResource($this->service->summary()));
    }

    public function enrollmentsByGroup(Request $request): JsonResponse
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to',   now()->toDateString());
        return ApiResponse::success($this->service->enrollmentsByGroup($from, $to));
    }

    public function attendanceOverview(Request $request): JsonResponse
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to',   now()->toDateString());
        return ApiResponse::success($this->service->attendanceOverview($from, $to));
    }

    public function incomeByDay(Request $request): JsonResponse
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to',   now()->toDateString());
        return ApiResponse::success($this->service->incomeByDay($from, $to));
    }

    public function paymentBreakdown(Request $request): JsonResponse
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to',   now()->toDateString());
        return ApiResponse::success($this->service->paymentBreakdown($from, $to));
    }

    public function financeSummary(Request $request): JsonResponse
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to',   now()->toDateString());
        return ApiResponse::success($this->service->financeSummary($from, $to));
    }

    public function cashFlow(Request $request): JsonResponse
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to',   now()->toDateString());
        return ApiResponse::success($this->service->cashFlow($from, $to));
    }

    public function expensesByType(Request $request): JsonResponse
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to',   now()->toDateString());
        return ApiResponse::success($this->service->expensesByType($from, $to));
    }

    public function employeePaymentsSummary(Request $request): JsonResponse
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to',   now()->toDateString());
        return ApiResponse::success($this->service->employeePaymentsSummary($from, $to));
    }

    public function pendingAdvances(): JsonResponse
    {
        return ApiResponse::success($this->service->pendingAdvances());
    }
}