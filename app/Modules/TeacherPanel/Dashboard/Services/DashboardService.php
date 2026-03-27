<?php

namespace App\Modules\TeacherPanel\Dashboard\Services;

use App\Modules\TeacherPanel\Dashboard\Repositories\DashboardRepository;
use App\Modules\TeacherPanel\Shared\TeacherContext;

class DashboardService
{
    public function __construct(
        private readonly DashboardRepository $repository,
    ) {}

    public function summary(): array
    {
        return $this->repository->summary(TeacherContext::teacherId());
    }

    public function upcomingClasses(): array
    {
        return $this->repository->upcomingClasses(TeacherContext::teacherId())->toArray();
    }

    public function recentAttendance(): array
    {
        return $this->repository->recentAttendance(TeacherContext::teacherId())->toArray();
    }
}
