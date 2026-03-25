<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\Administrator\Academy\Services\StudentService;
use App\Modules\Administrator\Academy\Http\Requests\Student\StudentRequest;
use App\Modules\Administrator\Academy\Http\Requests\Student\StudentUserRequest;
use App\Modules\Administrator\Academy\Http\Resources\Student\StudentDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Student\StudentSelectItemResource;

class StudentController
{
    public function __construct(
        private StudentService $studentService
    ) {}

    public function dataTable(Request $request)
    {
        $item = $this->studentService->dataTable($request);
        $item['data'] = StudentDataTableItemResource::collection($item['data']);
        return ApiResponse::success($item);
    }

    public function save(StudentRequest $request)
    {
        $data = $request->validated();
        $this->studentService->save($data);
        return ApiResponse::success($data, 'Estudiante creado correctamente');
    }

    public function saveUser(StudentUserRequest $request)
    {
        $data = $request->validated();
        $this->studentService->saveUser($data);
        return ApiResponse::success(null, 'Usuario actualizado correctamente');
    }

    public function selectAsyncItems(Request $request)
    {
        $item = $this->studentService->selectAsyncItems($request);
        $item = StudentSelectItemResource::collection($item);
        return ApiResponse::success($item);
    }
}
