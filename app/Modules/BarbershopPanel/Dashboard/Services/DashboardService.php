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
}