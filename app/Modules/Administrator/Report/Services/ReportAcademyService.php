<?php

namespace App\Modules\Administrator\Report\Services;

use App\Modules\Administrator\Report\Repositories\ReportAcademyRepository;
use Illuminate\Http\Request;

class ReportAcademyService
{
    public function __construct(
        private readonly ReportAcademyRepository $repository,
    ) {}

    public function summary(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->summary($from, $to, $branchId);
    }

    public function enrollmentTrend(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->enrollmentTrend($from, $to, $branchId);
    }

    public function revenueByType(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->revenueByType($from, $to, $branchId);
    }

    public function groupOccupancy(Request $request): array
    {
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->groupOccupancy($branchId);
    }

    public function groupDetail(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->groupDetail($from, $to, $branchId);
    }

    public function dailyIncome(Request $request): array
    {
        $date = $request->input('date', now()->toDateString());
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->dailyIncome($date, $branchId);
    }

    private function getDateRange(Request $request): array
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString()) . ' 23:59:59';

        return [$from, $to];
    }
}
