<?php

namespace App\Modules\AcademyPanel\Academy\Http\Controllers;

use App\Common\Http\Context\AdminContext;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\AcademyPanel\Academy\Http\Requests\TeacherRequest;
use App\Modules\AcademyPanel\Academy\Http\Resources\TeacherDetailResource;
use App\Modules\AcademyPanel\Academy\Services\TeacherService;
use App\Modules\Administrator\Academy\Http\Resources\Teacher\TeacherDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Teacher\TeacherSelectItemResource;
use Illuminate\Http\Request;

class TeacherController
{
    public function __construct(private TeacherService $teacherService) {}

    public function dataTable(Request $request)
    {
        $items = $this->teacherService->dataTable($request);
        $items['data'] = TeacherDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(TeacherRequest $request)
    {
        $data = $request->validated();
        $data['branch_id'] = AdminContext::academyBranchId();
        $this->teacherService->save($data);
        return ApiResponse::success(null, 'Docente guardado correctamente');
    }

    public function selectAsyncItems(Request $request)
    {
        return ApiResponse::success(TeacherSelectItemResource::collection(
            $this->teacherService->selectAsyncItems($request)
        ));
    }

    public function detail(int $id)
    {
        $teacher = $this->teacherService->detail($id);
        return ApiResponse::success(new TeacherDetailResource($teacher));
    }

    public function generatePdf(int $id)
    {
        return $this->teacherService->generatePdf($id);
    }
}