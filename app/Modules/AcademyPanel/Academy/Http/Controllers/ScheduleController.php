<?php
namespace App\Modules\AcademyPanel\Academy\Http\Controllers;
use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\AcademyPanel\Academy\Services\ScheduleService;
use App\Modules\Administrator\Academy\Http\Requests\Schedule\ScheduleRequest;
use App\Modules\Administrator\Academy\Http\Resources\Schedule\ScheduleDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Schedule\ScheduleSelectItemResource;
class ScheduleController {
    public function __construct(private ScheduleService $scheduleService) {}
    public function dataTable(Request $request) { $i = $this->scheduleService->dataTable($request); $i['data'] = ScheduleDataTableItemResource::collection($i['data']); return ApiResponse::success($i); }
    public function save(ScheduleRequest $request) { $this->scheduleService->save($request->validated()); return ApiResponse::success(null, 'Horario guardado correctamente'); }
    public function delete(int $id) { $this->scheduleService->delete($id); return ApiResponse::success(null, 'Horario eliminado correctamente'); }
    public function selectItems() { return ApiResponse::success(ScheduleSelectItemResource::collection($this->scheduleService->getActiveSchedules())); }
}