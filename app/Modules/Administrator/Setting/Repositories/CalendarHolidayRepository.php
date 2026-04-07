<?php

namespace App\Modules\Administrator\Setting\Repositories;

use App\Models\Core\CalendarHoliday;

class CalendarHolidayRepository
{
    public function getByMonth(int $year, int $month)
    {
        return CalendarHoliday::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'asc')
            ->get();
    }

    public function createOrUpdate(array $data)
    {
        $id = $data['id'] ?? null;
        return CalendarHoliday::updateOrCreate(['id' => $id], $data);
    }

    public function delete(int $id)
    {
        $holiday = CalendarHoliday::findOrFail($id);
        $holiday->delete();
        return $holiday;
    }
}
