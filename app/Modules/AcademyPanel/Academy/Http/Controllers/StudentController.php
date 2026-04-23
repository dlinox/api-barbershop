<?php

namespace App\Modules\AcademyPanel\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\AcademyPanel\Academy\Services\StudentService;
use App\Modules\Administrator\Academy\Http\Requests\Student\StudentRequest;
use App\Modules\Administrator\Academy\Http\Resources\Student\StudentDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Student\StudentSelectItemResource;
use Illuminate\Http\Request;

class StudentController
{
    public function __construct(private StudentService $studentService) {}

    public function dataTable(Request $request)
    {
        $items = $this->studentService->dataTable($request);
        $items['data'] = StudentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(StudentRequest $request)
    {
        $this->studentService->save($request->validated());
        return ApiResponse::success(null, 'Alumno guardado correctamente');
    }

    public function selectAsyncItems(Request $request)
    {
        return ApiResponse::success(StudentSelectItemResource::collection(
            $this->studentService->selectAsyncItems($request)
        ));
    }
}