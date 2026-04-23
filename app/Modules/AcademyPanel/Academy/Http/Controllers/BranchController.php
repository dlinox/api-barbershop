<?php
namespace App\Modules\AcademyPanel\Academy\Http\Controllers;
use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\AcademyPanel\Academy\Services\BranchService;
use App\Modules\Administrator\Academy\Http\Requests\Branch\BranchRequest;
use App\Modules\Administrator\Academy\Http\Resources\Branch\BranchDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Branch\BranchSelectItemResource;
class BranchController {
    public function __construct(private BranchService $branchService) {}
    public function dataTable(Request $request) { $i = $this->branchService->dataTable($request); $i['data'] = BranchDataTableItemResource::collection($i['data']); return ApiResponse::success($i); }
    public function save(BranchRequest $request) { $this->branchService->save($request->validated()); return ApiResponse::success(null, 'Sucursal guardada correctamente'); }
    public function delete(int $id) { $this->branchService->delete($id); return ApiResponse::success(null, 'Sucursal eliminada correctamente'); }
    public function selectItems() { return ApiResponse::success(BranchSelectItemResource::collection($this->branchService->getActiveBranches())); }
}