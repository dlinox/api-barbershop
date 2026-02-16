<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Academy\Http\Requests\Group\GroupRequest;
use App\Modules\Administrator\Academy\Http\Resources\Group\GroupDataTableItemResource;
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

    public function getItemsByStudent($studentId)
    {
        $items = $this->groupService->getActiveAndUpcomingGroups();

        $items = GroupSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
