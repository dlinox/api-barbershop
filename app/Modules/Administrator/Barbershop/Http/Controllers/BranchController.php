<?php

namespace App\Modules\Administrator\Barbershop\Http\Controllers;

use Illuminate\Http\Request;

use App\Common\Http\Responses\ApiResponse;

use App\Modules\Administrator\Barbershop\Services\BranchService;

use App\Modules\Administrator\Barbershop\Http\Requests\Branch\BranchRequest;
use App\Modules\Administrator\Barbershop\Http\Resources\Branch\BranchDataTableItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Branch\BranchSelectItemResource;

class BranchController
{

    public function __construct(
        private BranchService $branchService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->branchService->dataTable($request);
        $items['data'] = BranchDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(BranchRequest $request)
    {
        $data = $request->validated();
        $this->branchService->save($data);
        return ApiResponse::success($data, 'Sucursal guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->branchService->delete($id);
        return ApiResponse::success(null, 'Sucursal eliminada correctamente');
    }

    public function selectItems()
    {
        $items = $this->branchService->getActiveBranches();
        $items = BranchSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
