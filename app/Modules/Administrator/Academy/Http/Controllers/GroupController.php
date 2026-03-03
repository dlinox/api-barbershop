<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Academy\Http\Requests\Group\AssignTeacherRequest;
use App\Modules\Administrator\Academy\Http\Requests\Group\GroupRequest;
use App\Modules\Administrator\Academy\Http\Resources\Group\GroupDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Group\EnrollmentGroupItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Group\GroupSelectItemResource;
use App\Modules\Administrator\Academy\Services\GroupService;
use Illuminate\Http\Request;

class GroupController
{
    public function __construct(
        private GroupService $groupService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->groupService->dataTable($request);
        $items['data'] = GroupDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(GroupRequest $request)
    {
        $data = $request->validated();
        $this->groupService->save($data);
        return ApiResponse::success('Grupo guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->groupService->delete($id);
        return ApiResponse::success(null, 'Grupo eliminado correctamente');
    }

    public function getAvailableEnrollmentGroups($studentId)
    {
        $items = $this->groupService->getAvailableEnrollmentGroups($studentId);

        $items = EnrollmentGroupItemResource::collection($items);
        return ApiResponse::success($items);
    }

    public function getActiveAndUpcoming()
    {
        $items = $this->groupService->getActiveAndUpcoming();
        $items = GroupSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }

    public function assignTeacher(AssignTeacherRequest $request)
    {
        $data = $request->validated();
        $this->groupService->assignTeacher($data);
        return ApiResponse::success(null, 'Docente asignado correctamente');
    }

    public function selectItems()
    {
        $items = $this->groupService->selectItems();
        $items = GroupSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
