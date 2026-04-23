<?php

namespace App\Modules\AcademyPanel\Dashboard\Services;

use App\Common\Http\Context\AdminContext;
use App\Modules\AcademyPanel\Dashboard\Repositories\DashboardRepository;

class DashboardService
{
    public function __construct(
        private readonly DashboardRepository $repository,
    ) {}

    public function summary(): array
    {
        return $this->repository->summary(AdminContext::academyBranchId());
    }

    public function enrollmentsByGroup(string $from, string $to): array
    {
        return $this->repository->enrollmentsByGroup(AdminContext::academyBranchId(), $from, $to)->toArray();
    }

    public function attendanceOverview(string $from, string $to): array
    {
        return $this->repository->attendanceOverview(AdminContext::academyBranchId(), $from, $to);
    }

    public function incomeByDay(string $from, string $to): array
    {
        return $this->repository->incomeByDay(AdminContext::academyBranchId(), $from, $to)->toArray();
    }

    public function paymentBreakdown(string $from, string $to): array
    {
        return $this->repository->paymentBreakdown(AdminContext::academyBranchId(), $from, $to);
    }
}