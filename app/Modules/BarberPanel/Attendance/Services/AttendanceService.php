<?php

namespace App\Modules\BarberPanel\Attendance\Services;

use App\Modules\BarberPanel\Attendance\Repositories\AttendanceRepository;
use App\Modules\BarberPanel\Shared\BarberContext;

class AttendanceService
{
    public function __construct(
        private readonly AttendanceRepository $repository,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($request, BarberContext::barberId());
    }
}
