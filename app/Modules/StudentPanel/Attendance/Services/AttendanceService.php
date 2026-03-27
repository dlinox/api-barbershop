<?php

namespace App\Modules\StudentPanel\Attendance\Services;

use App\Modules\StudentPanel\Attendance\Repositories\AttendanceRepository;
use App\Modules\StudentPanel\Shared\StudentContext;

class AttendanceService
{
    public function __construct(
        private readonly AttendanceRepository $repository,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($request, StudentContext::studentId());
    }
}
