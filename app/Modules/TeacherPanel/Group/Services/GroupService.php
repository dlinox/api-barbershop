<?php

namespace App\Modules\TeacherPanel\Group\Services;

use App\Modules\TeacherPanel\Group\Repositories\GroupRepository;
use App\Modules\TeacherPanel\Shared\TeacherContext;

class GroupService
{
    public function __construct(
        private readonly GroupRepository $repository,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($request, TeacherContext::teacherId());
    }

    public function students(int $groupId)
    {
        return $this->repository->students($groupId, TeacherContext::teacherId());
    }

    public function attendanceDeadlineStatus(int $groupId, string $date)
    {
        return $this->repository->attendanceDeadlineStatus($groupId, $date, TeacherContext::teacherId());
    }

    public function startAttendanceDeadline(int $groupId, string $date): void
    {
        $this->repository->startAttendanceDeadline($groupId, $date, TeacherContext::teacherId());
    }

    public function attendances(int $groupId, string $date)
    {
        return $this->repository->attendances($groupId, $date, TeacherContext::teacherId());
    }

    public function updateAttendanceStatus(int $groupId, int $enrollmentId, string $date, string $status): void
    {
        $this->repository->updateAttendanceStatus($groupId, $enrollmentId, $date, $status, TeacherContext::teacherId());
    }
}
