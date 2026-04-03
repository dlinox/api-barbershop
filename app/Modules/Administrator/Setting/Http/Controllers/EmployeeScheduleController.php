<?php

namespace App\Modules\Administrator\Setting\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Setting\Http\Requests\EmployeeSchedule\EmployeeScheduleRequest;
use App\Modules\Administrator\Setting\Http\Resources\EmployeeSchedule\EmployeeScheduleResource;
use App\Modules\Administrator\Setting\Services\EmployeeScheduleService;

class EmployeeScheduleController
{
    public function __construct(
        private EmployeeScheduleService $employeeScheduleService
    ) {}

    public function get()
    {
        $schedules = $this->employeeScheduleService->getAll();
        return ApiResponse::success(EmployeeScheduleResource::collection($schedules));
    }

    public function save(EmployeeScheduleRequest $request)
    {
        $data = $request->validated();
        $this->employeeScheduleService->save($data);
        return ApiResponse::success(null, 'Horarios guardados correctamente');
    }
}
