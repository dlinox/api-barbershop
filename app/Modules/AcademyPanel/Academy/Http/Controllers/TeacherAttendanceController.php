<?php

namespace App\Modules\AcademyPanel\Academy\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\AcademyPanel\Academy\Services\TeacherAttendanceService;
use App\Modules\Administrator\Academy\Http\Resources\TeacherAttendance\TeacherAttendanceDataTableResource;

class TeacherAttendanceController
{
    public function __construct(private TeacherAttendanceService $teacherAttendanceService) {}

    public function dataTable(Request $request, ?string $date = null)
    {
        $request->merge(['date' => $date]);
        $items = $this->teacherAttendanceService->dataTable($request);
        $items['data'] = TeacherAttendanceDataTableResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function registerCheckIn(Request $request)
    {
        $this->teacherAttendanceService->registerCheckIn($request->all());
        return ApiResponse::success(null, 'Entrada registrada correctamente');
    }

    public function registerCheckOut(Request $request)
    {
        $this->teacherAttendanceService->registerCheckOut($request->all());
        return ApiResponse::success(null, 'Salida registrada correctamente');
    }

    public function update(Request $request)
    {
        $this->teacherAttendanceService->update($request->all());
        return ApiResponse::success(null, 'Asistencia actualizada correctamente');
    }

    public function registerAbsent(Request $request)
    {
        $this->teacherAttendanceService->registerAbsent($request->all());
        return ApiResponse::success(null, 'Falta registrada correctamente');
    }

    public function generateQrCode()
    {
        return ApiResponse::success($this->teacherAttendanceService->generateQrCode());
    }
}