<?php
namespace App\Modules\AcademyPanel\Academy\Http\Controllers;
use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\AcademyPanel\Academy\Services\MaterialService;
use App\Modules\AcademyPanel\Academy\Http\Requests\MaterialRequest;
use App\Modules\Administrator\Academy\Http\Resources\Material\MaterialDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Material\MaterialSelectItemResource;
class MaterialController {
    public function __construct(private MaterialService $materialService) {}
    public function dataTable(Request $request) { $i = $this->materialService->dataTable($request); $i['data'] = MaterialDataTableItemResource::collection($i['data']); return ApiResponse::success($i); }
    public function save(MaterialRequest $request) { $this->materialService->save($request->validated()); return ApiResponse::success(null, 'Material guardado correctamente'); }
    public function delete(int $id) { $this->materialService->delete($id); return ApiResponse::success(null, 'Material eliminado correctamente'); }
    public function selectItems() { return ApiResponse::success(MaterialSelectItemResource::collection($this->materialService->getActiveMaterials())); }
}