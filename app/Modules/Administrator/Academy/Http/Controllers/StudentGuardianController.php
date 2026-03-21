<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;

use App\Modules\Administrator\Academy\Services\StudentGuardianService;
use App\Modules\Administrator\Academy\Http\Requests\StudentGuardian\StudentGuardianRequest;
use App\Modules\Administrator\Academy\Http\Resources\StudentGuardian\StudentGuardianResource;

class StudentGuardianController
{
    public function __construct(
        private StudentGuardianService $studentGuardianService
    ) {}

    public function findByStudentId(int $studentId)
    {
        $items = $this->studentGuardianService->findByStudentId($studentId);
        $items = StudentGuardianResource::collection($items);
        return ApiResponse::success($items);
    }

    public function save(StudentGuardianRequest $request)
    {
        $data = $request->validated();
        $this->studentGuardianService->save($data);
        return ApiResponse::success($data, 'Apoderado guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->studentGuardianService->delete($id);
        return ApiResponse::success(null, 'Apoderado eliminado correctamente');
    }
}
