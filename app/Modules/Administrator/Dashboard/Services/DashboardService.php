<?php

namespace App\Modules\Administrator\Dashboard\Services;

use App\Modules\Administrator\Dashboard\Repositories\DashboardRepository;
use Illuminate\Http\Request;

class DashboardService
{
    public function __construct(
        private readonly DashboardRepository $repository,
    ) {}

    public function summary(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);

        return $this->repository->summary($from, $to);
    }

    public function revenueChart(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $groupBy = $request->input('group_by', 'month');

        $format = match ($groupBy) {
            'day'   => '%Y-%m-%d',
            'week'  => '%x-W%v',
            default => '%Y-%m',
        };

        $data = $this->repository->revenueChart($from, $to, $format);
        $allPeriods = $this->repository->generatePeriods($from, $to, $groupBy);

        return [
            'categories' => $allPeriods,
            'series' => [
                ['name' => 'Barbería', 'data' => $allPeriods->map(fn($p) => (float) ($data['ticketRevenue'][$p] ?? 0))->values()],
                ['name' => 'Academia', 'data' => $allPeriods->map(fn($p) => (float) ($data['academyRevenue'][$p] ?? 0))->values()],
                ['name' => 'Ventas', 'data' => $allPeriods->map(fn($p) => (float) ($data['saleRevenue'][$p] ?? 0))->values()],
            ],
        ];
    }

    public function ticketsByBarber(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);

        return $this->repository->ticketsByBarber($from, $to);
    }

    public function topServices(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $limit = $request->input('limit', 10);

        return $this->repository->topServices($from, $to, $limit);
    }

    public function enrollmentsByGroup(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);

        return $this->repository->enrollmentsByGroup($from, $to);
    }

    public function attendanceOverview(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);

        return $this->repository->attendanceOverview($from, $to);
    }

    public function cashFlowChart(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);

        return $this->repository->cashFlowChart($from, $to);
    }

    public function lowStockAlerts(Request $request)
    {
        $limit = $request->input('limit', 15);

        return $this->repository->lowStockAlerts($limit);
    }

    public function recentTickets(Request $request)
    {
        $limit = $request->input('limit', 10);

        return $this->repository->recentTickets($limit);
    }

    public function reservationsByStatus(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);

        return $this->repository->reservationsByStatus($from, $to);
    }

    public function payrollSummary(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);

        return $this->repository->payrollSummary($from, $to);
    }

    // ─── Private Helpers ─────────────────────────────────────

    private function getDateRange(Request $request): array
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString()) . ' 23:59:59';

        return [$from, $to];
    }
}
