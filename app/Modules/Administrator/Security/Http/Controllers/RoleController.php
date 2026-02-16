<?php

namespace App\Modules\Administrator\Security\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Security\Services\RoleService;

use App\Modules\Administrator\Security\Http\Requests\Role\RoleRequest;

use App\Modules\Administrator\Security\Http\Resources\Role\RoleDataTableResource;
use App\Modules\Administrator\Security\Http\Resources\Role\RoleSelectItemResource;

class RoleController
{
    public function __construct(
        private readonly RoleService $roleService
    ) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->roleService->dataTable($request);
        $items['data'] = RoleDataTableResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(RoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createOrUpdate($request->validated());

        return ApiResponse::success($role, 'Rol guardado correctamente', 201);
    }

    public function selectItems(): JsonResponse
    {
        $roles = $this->roleService->getRolesForAdmins();
        $roles = RoleSelectItemResource::collection($roles);
        return ApiResponse::success($roles);
    }
}
