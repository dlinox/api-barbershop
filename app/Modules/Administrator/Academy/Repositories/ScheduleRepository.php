<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Academy\Schedule;

class ScheduleRepository
{
    public function dataTable($request)
    {
        return Schedule::dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        $schedule = Schedule::updateOrCreate(['id' => $data['id']], $data);
        return $schedule;
    }

    public function delete(int $id)
    {
        $schedule = Schedule::find($id);

        if ($schedule->groups()->exists()) {
            throw new \Exception('No se puede eliminar el horario porque tiene grupos asociados');
        }

        return $schedule->delete();
    }

    public function getActiveSchedules()
    {
        return Schedule::where('is_active', true)->get();
    }
}
