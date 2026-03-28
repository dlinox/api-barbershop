<?php

namespace App\Modules\BarberPanel\Dashboard\Services;

use App\Modules\BarberPanel\Dashboard\Repositories\DashboardRepository;
use App\Modules\BarberPanel\Shared\BarberContext;

class DashboardService
{
    public function __construct(
        private readonly DashboardRepository $repository,
    ) {}

    public function summary(): array
    {
        return $this->repository->summary(BarberContext::barberId());
    }

    public function recentTickets(): array
    {
        return $this->repository->recentTickets(BarberContext::barberId())->toArray();
    }

    public function recentAttendance(): array
    {
        return $this->repository->recentAttendance(BarberContext::barberId())->toArray();
    }
}
