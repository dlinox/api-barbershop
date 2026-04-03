<?php

namespace App\Modules\Administrator\Setting\Repositories;

use App\Models\Treasury\EmployeeSchedule;

class EmployeeScheduleRepository
{
    public function getAll()
    {
        return EmployeeSchedule::all();
    }

    public function save(array $data)
    {
        foreach ($data['schedules'] as $schedule) {
            EmployeeSchedule::updateOrCreate(
                ['type' => $schedule['type']],
                [
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'is_active' => $schedule['is_active'] ?? true,
                ]
            );
        }
    }
}
