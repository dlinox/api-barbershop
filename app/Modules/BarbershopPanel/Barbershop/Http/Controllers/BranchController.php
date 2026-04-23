<?php

namespace App\Modules\BarbershopPanel\Barbershop\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\BarbershopPanel\Barbershop\Services\BranchService;
use App\Modules\Administrator\Barbershop\Http\Requests\Branch\BranchRequest;
use App\Modules\Administrator\Barbershop\Http\Resources\Branch\BranchDataTableItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Branch\BranchSelectItemResource;

class BranchController
{
    public function __construct(private BranchService $branchService) {}

    public function dataTable(Request $request)
    {
        $items = $this->branchService->dataTable($request);
        $items['data'] = BranchDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(BranchRequest $request)
    {
        $this->branchService->save($request->validated());
        return ApiResponse::success(null, 'Sucursal guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->branchService->delete($id);
        return ApiResponse::success(null, 'Sucursal eliminada correctamente');
    }

    public function selectItems()
    {
        $items = BranchSelectItemResource::collection($this->branchService->getActiveBranches());
        return ApiResponse::success($items);
    }
}