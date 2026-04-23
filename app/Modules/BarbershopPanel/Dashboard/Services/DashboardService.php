<?php

namespace App\Modules\BarbershopPanel\Dashboard\Services;

use App\Common\Http\Context\AdminContext;
use App\Modules\BarbershopPanel\Dashboard\Repositories\DashboardRepository;

class DashboardService
{
    public function __construct(
        private readonly DashboardRepository $repository,
    ) {}

    public function summary(): array
    {
        return $this->repository->summary(AdminContext::barbershopBranchId());
    }

    public function ticketsByBarber(string $from, string $to): array
    {
        return $this->repository->ticketsByBarber(AdminContext::barbershopBranchId(), $from, $to)->toArray();
    }

    public function topServices(string $from, string $to): array
    {
        return $this->repository->topServices(AdminContext::barbershopBranchId(), $from, $to)->toArray();
    }

    public function revenueByDay(string $from, string $to): array
    {
        return $this->repository->revenueByDay(AdminContext::barbershopBranchId(), $from, $to)->toArray();
    }

    public function recentTickets(): array
    {
        return $this->repository->recentTickets(AdminContext::barbershopBranchId())->toArray();
    }

    public function paymentBreakdown(string $from, string $to): array
    {
        return $this->repository->paymentBreakdown(AdminContext::barbershopBranchId(), $from, $to);
    }

    public function financeSummary(string $from, string $to): array
    {
        return $this->repository->financeSummary(
            AdminContext::barbershopBranchId(),
            AdminContext::infrastructureId(),
            $from,
            $to
        );
    }

    public function cashFlow(string $from, string $to): array
    {
        return $this->repository->cashFlow(
            AdminContext::barbershopBranchId(),
            AdminContext::infrastructureId(),
            $from,
            $to
        );
    }

    public function expensesByType(string $from, string $to): array
    {
        return $this->repository->expensesByType(AdminContext::infrastructureId(), $from, $to);
    }

    public function employeePaymentsSummary(string $from, string $to): array
    {
        return $this->repository->employeePaymentsSummary(
            AdminContext::barbershopBranchId(),
            AdminContext::infrastructureId(),
            $from,
            $to
        );
    }

    public function pendingAdvances(): array
    {
        return $this->repository->pendingAdvances(
            AdminContext::barbershopBranchId(),
            AdminContext::infrastructureId()
        );
    }
}