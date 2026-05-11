<?php

namespace App\Modules\AcademyPanel\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\AcademyPanel\Academy\Services\StudentGuardianService;
use App\Modules\AcademyPanel\Academy\Http\Requests\StudentGuardianRequest;
use App\Modules\AcademyPanel\Academy\Http\Resources\StudentGuardianResource;

class StudentGuardianController
{
    public function __construct(private StudentGuardianService $studentGuardianService) {}

    public function findByStudentId(int $studentId)
    {
        $items = $this->studentGuardianService->findByStudentId($studentId);
        return ApiResponse::success(StudentGuardianResource::collection($items));
    }

    public function save(StudentGuardianRequest $request)
    {
        $this->studentGuardianService->save($request->validated());
        return ApiResponse::success(null, 'Apoderado guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->studentGuardianService->delete($id);
        return ApiResponse::success(null, 'Apoderado eliminado correctamente');
    }
}