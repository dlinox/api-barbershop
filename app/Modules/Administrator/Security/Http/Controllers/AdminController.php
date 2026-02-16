<?php

namespace App\Modules\Administrator\Security\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Common\Http\Responses\ApiResponse;

use App\Modules\Administrator\Security\Http\Requests\Admin\AdminCreateRequest;
use App\Modules\Administrator\Security\Services\AdminService;
use App\Modules\Administrator\Security\Http\Resources\Admin\AdminDataTableItemResource;

class AdminController
{
    public function __construct(private AdminService $adminService) {}

    public function dataTable(Request $request)
    {
        $items = $this->adminService->dataTable($request);
        $items['data'] = AdminDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }
    public function save(AdminCreateRequest $request): JsonResponse
    {
        $req = $request->validated();
        $this->adminService->create($req);
        return ApiResponse::success(null, 'Admin created successfully', 201);
    }
}
