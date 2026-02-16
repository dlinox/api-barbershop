<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\ScheduleRepository;
use Illuminate\Http\Request;

class ScheduleService
{
    public function __construct(
        private ScheduleRepository $scheduleRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->scheduleRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->scheduleRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->scheduleRepository->delete($id);
    }

    public function getActiveSchedules()
    {
        return $this->scheduleRepository->getActiveSchedules();
    }
}
