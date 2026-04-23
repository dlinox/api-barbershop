<?php
namespace App\Modules\AcademyPanel\Academy\Http\Controllers;
use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\AcademyPanel\Academy\Services\LevelService;
use App\Modules\Administrator\Academy\Http\Requests\Level\LevelRequest;
use App\Modules\Administrator\Academy\Http\Resources\Level\LevelDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Level\LevelSelectItemResource;
class LevelController {
    public function __construct(private LevelService $levelService) {}
    public function dataTable(Request $request) { $i = $this->levelService->dataTable($request); $i['data'] = LevelDataTableItemResource::collection($i['data']); return ApiResponse::success($i); }
    public function save(LevelRequest $request) { $this->levelService->save($request->validated()); return ApiResponse::success(null, 'Nivel guardado correctamente'); }
    public function delete(int $id) { $this->levelService->delete($id); return ApiResponse::success(null, 'Nivel eliminado correctamente'); }
    public function selectItems() { return ApiResponse::success(LevelSelectItemResource::collection($this->levelService->getActiveLevels())); }
}