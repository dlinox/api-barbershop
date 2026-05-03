<?php

namespace App\Modules\AcademyPanel\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\AcademyPanel\Academy\Services\GroupService;
use App\Modules\Administrator\Academy\Http\Requests\Group\AssignTeacherRequest;
use App\Modules\Administrator\Academy\Http\Requests\Group\GroupRequest;
use App\Modules\Administrator\Academy\Http\Resources\Group\GroupDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Group\EnrollmentGroupItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Group\GroupSelectItemResource;
use Illuminate\Http\Request;

class GroupController
{
    public function __construct(private GroupService $groupService) {}

    public function dataTable(Request $request)
    {
        $items = $this->groupService->dataTable($request);
        $items['data'] = GroupDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(GroupRequest $request)
    {
        $this->groupService->save($request->validated());
        return ApiResponse::success(null, 'Grupo guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->groupService->delete($id);
        return ApiResponse::success(null, 'Grupo eliminado correctamente');
    }

    public function cancel(int $id)
    {
        $this->groupService->cancel($id);
        return ApiResponse::success(null, 'Grupo cancelado correctamente');
    }

    public function assignTeacher(AssignTeacherRequest $request)
    {
        $this->groupService->assignTeacher($request->validated());
        return ApiResponse::success(null, 'Docente asignado correctamente');
    }

    public function checkTeacher(int $groupId, int $teacherId)
    {
        $result = $this->groupService->checkTeacher($groupId, $teacherId);
        return ApiResponse::success($result);
    }

    public function selectItems()
    {
        return ApiResponse::success(GroupSelectItemResource::collection($this->groupService->selectItems()));
    }

    public function getAvailableEnrollmentGroups(int $studentId)
    {
        return ApiResponse::success(EnrollmentGroupItemResource::collection(
            $this->groupService->getAvailableEnrollmentGroups($studentId)
        ));
    }

    public function getActiveAndUpcoming()
    {
        return ApiResponse::success(EnrollmentGroupItemResource::collection($this->groupService->getActiveAndUpcoming()));
    }
}