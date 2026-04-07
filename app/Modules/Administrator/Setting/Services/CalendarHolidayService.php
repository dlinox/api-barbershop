<?php

namespace App\Modules\Administrator\Setting\Services;

use App\Modules\Administrator\Setting\Repositories\CalendarHolidayRepository;

class CalendarHolidayService
{
    public function __construct(
        private CalendarHolidayRepository $calendarHolidayRepository
    ) {}

    public function getByMonth(int $year, int $month)
    {
        return $this->calendarHolidayRepository->getByMonth($year, $month);
    }

    public function save(array $data)
    {
        return $this->calendarHolidayRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->calendarHolidayRepository->delete($id);
    }
}
