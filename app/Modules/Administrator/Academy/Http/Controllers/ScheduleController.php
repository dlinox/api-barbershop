<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Academy\Services\ScheduleService;
use App\Modules\Administrator\Academy\Http\Requests\Schedule\ScheduleRequest;
use App\Modules\Administrator\Academy\Http\Resources\Schedule\ScheduleDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Schedule\ScheduleSelectItemResource;

class ScheduleController
{
    public function __construct(
        private ScheduleService $scheduleService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->scheduleService->dataTable($request);
        $items['data'] = ScheduleDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(ScheduleRequest $request)
    {
        $data = $request->validated();
        $this->scheduleService->save($data);
        return ApiResponse::success('Horario guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->scheduleService->delete($id);
        return ApiResponse::success(null, 'Horario eliminado correctamente');
    }

    public function selectItems()
    {
        $items = $this->scheduleService->getActiveSchedules();
        $items = ScheduleSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
