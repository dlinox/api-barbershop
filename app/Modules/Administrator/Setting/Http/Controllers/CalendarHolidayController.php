<?php

namespace App\Modules\Administrator\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Setting\Services\CalendarHolidayService;
use App\Modules\Administrator\Setting\Http\Requests\CalendarHoliday\CalendarHolidayRequest;
use App\Modules\Administrator\Setting\Http\Resources\CalendarHoliday\CalendarHolidayResource;

class CalendarHolidayController
{
    public function __construct(
        private CalendarHolidayService $calendarHolidayService
    ) {}

    public function getByMonth(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $items = $this->calendarHolidayService->getByMonth(
            $request->year,
            $request->month
        );
        $items = CalendarHolidayResource::collection($items);
        return ApiResponse::success($items);
    }

    public function save(CalendarHolidayRequest $request)
    {
        $data = $request->validated();
        $this->calendarHolidayService->save($data);
        return ApiResponse::success($data, 'Día festivo guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->calendarHolidayService->delete($id);
        return ApiResponse::success(null, 'Día festivo eliminado correctamente');
    }
}
