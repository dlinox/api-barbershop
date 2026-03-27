<?php

namespace App\Modules\StudentPanel\Dashboard\Services;

use App\Modules\StudentPanel\Dashboard\Repositories\DashboardRepository;
use App\Modules\StudentPanel\Shared\StudentContext;

class DashboardService
{
    public function __construct(
        private readonly DashboardRepository $repository,
    ) {}

    public function summary(): array
    {
        return $this->repository->summary(StudentContext::studentId());
    }

    public function upcomingClasses(): array
    {
        return $this->repository->upcomingClasses(StudentContext::studentId())->toArray();
    }

    public function recentAttendance(): array
    {
        return $this->repository->recentAttendance(StudentContext::studentId())->toArray();
    }
}
