<?php

namespace App\Modules\TeacherPanel\Attendance\Services;

use App\Modules\TeacherPanel\Attendance\Repositories\AttendanceRepository;
use App\Modules\TeacherPanel\Shared\TeacherContext;

class AttendanceService
{
    public function __construct(
        private readonly AttendanceRepository $repository,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($request, TeacherContext::teacherId());
    }
}
