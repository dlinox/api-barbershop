<?php

namespace App\Modules\BarbershopPanel\Dashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\BarbershopPanel\Dashboard\Http\Resources\DashboardSummaryResource;
use App\Modules\BarbershopPanel\Dashboard\Services\DashboardService;

class DashboardController
{
    public function __construct(
        private readonly DashboardService $service,
    ) {}

    public function summary(): JsonResponse
    {
        return ApiResponse::success(new DashboardSummaryResource($this->service->summary()));
    }

    public function ticketsByBarber(Request $request): JsonResponse
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to',   now()->toDateString());
        return ApiResponse::success($this->service->ticketsByBarber($from, $to));
    }

    public function topServices(Request $request): JsonResponse
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to',   now()->toDateString());
        return ApiResponse::success($this->service->topServices($from, $to));
    }

    public function revenueByDay(Request $request): JsonResponse
    {
        $from = $request->query('from', now()->startOfMonth()->toDateString());
        $to   = $request->query('to',   now()->toDateString());
        return ApiResponse::success($this->service->revenueByDay($from, $to));
    }

    public function recentTickets(): JsonResponse
    {
        return ApiResponse::success($this->service->recentTickets());
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