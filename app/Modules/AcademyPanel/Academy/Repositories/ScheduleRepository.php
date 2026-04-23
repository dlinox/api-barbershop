<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Academy\Schedule;

class ScheduleRepository
{
    public function dataTable($request)
    {
        $query = Schedule::query();
        if (empty($request->sortBy)) { $query->orderBy('id', 'desc'); }
        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data) { return Schedule::updateOrCreate(['id' => $data['id']], $data); }

    public function delete(int $id)
    {
        $schedule = Schedule::findOrFail($id);
        if ($schedule->groups()->exists()) throw new \Exception('No se puede eliminar el horario porque tiene grupos relacionados');
        $schedule->delete();
        return $schedule;
    }

    public function getActiveSchedules() { return Schedule::where('is_active', true)->get(); }
}