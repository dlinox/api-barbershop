<?php

namespace App\Modules\Administrator\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Services\WorkerAttendanceService;
use App\Modules\Administrator\Treasury\Http\Resources\WorkerAttendance\WorkerAttendanceDataTableResource;

class WorkerAttendanceController
{
    public function __construct(
        private WorkerAttendanceService $workerAttendanceService
    ) {}

    public function dataTable(Request $request, ?string $date = null)
    {
        $request->merge(['date' => $date]);
        $items = $this->workerAttendanceService->dataTable($request);
        $items['data'] = WorkerAttendanceDataTableResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function registerCheckIn(Request $request)
    {
        $this->workerAttendanceService->registerCheckIn($request->all());
        return ApiResponse::success(null, 'Entrada registrada correctamente');
    }

    public function registerCheckOut(Request $request)
    {
        $this->workerAttendanceService->registerCheckOut($request->all());
        return ApiResponse::success(null, 'Salida registrada correctamente');
    }

    public function update(Request $request)
    {
        $this->workerAttendanceService->update($request->all());
        return ApiResponse::success(null, 'Asistencia actualizada correctamente');
    }

    public function registerAbsent(Request $request)
    {
        $this->workerAttendanceService->registerAbsent($request->all());
        return ApiResponse::success(null, 'Falta registrada correctamente');
    }

    public function generateQrCode()
    {
        $data = $this->workerAttendanceService->generateQrCode();
        return ApiResponse::success($data);
    }

    public function history(int $workerId)
    {
        $items = $this->workerAttendanceService->history($workerId);
        return ApiResponse::success($items);
    }
}
