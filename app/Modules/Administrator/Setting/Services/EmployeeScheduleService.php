<?php

namespace App\Modules\Administrator\Setting\Services;

use App\Modules\Administrator\Setting\Repositories\EmployeeScheduleRepository;

class EmployeeScheduleService
{
    public function __construct(
        private EmployeeScheduleRepository $employeeScheduleRepository
    ) {}

    public function getAll()
    {
        return $this->employeeScheduleRepository->getAll();
    }

    public function save(array $data)
    {
        return $this->employeeScheduleRepository->save($data);
    }
}
