<?php

namespace App\Modules\BarbershopPanel\Barbershop\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\BarbershopPanel\Barbershop\Services\BarberAttendanceService;
use App\Modules\Administrator\Barbershop\Http\Resources\BarberAttendance\BarberAttendanceDataTableResource;

class BarberAttendanceController
{
    public function __construct(private BarberAttendanceService $barberAttendanceService) {}

    public function dataTable(Request $request, ?string $date = null)
    {
        $request->merge(['date' => $date]);
        $items = $this->barberAttendanceService->dataTable($request);
        $items['data'] = BarberAttendanceDataTableResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function registerCheckIn(Request $request)
    {
        $this->barberAttendanceService->registerCheckIn($request->all());
        return ApiResponse::success(null, 'Entrada registrada correctamente');
    }

    public function registerCheckOut(Request $request)
    {
        $this->barberAttendanceService->registerCheckOut($request->all());
        return ApiResponse::success(null, 'Salida registrada correctamente');
    }

    public function update(Request $request)
    {
        $this->barberAttendanceService->update($request->all());
        return ApiResponse::success(null, 'Asistencia actualizada correctamente');
    }

    public function registerAbsent(Request $request)
    {
        $this->barberAttendanceService->registerAbsent($request->all());
        return ApiResponse::success(null, 'Falta registrada correctamente');
    }

    public function generateQrCode()
    {
        $data = $this->barberAttendanceService->generateQrCode();
        return ApiResponse::success($data);
    }
}