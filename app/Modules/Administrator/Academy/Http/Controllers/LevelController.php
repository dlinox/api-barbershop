<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Academy\Services\LevelService;
use App\Modules\Administrator\Academy\Http\Requests\Level\LevelRequest;
use App\Modules\Administrator\Academy\Http\Resources\Level\LevelDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Level\LevelSelectItemResource;

class LevelController
{
    public function __construct(
        private LevelService $levelService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->levelService->dataTable($request);
        $items['data'] = LevelDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(LevelRequest $request)
    {
        $data = $request->validated();
        $this->levelService->save($data);
        return ApiResponse::success('Nivel guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->levelService->delete($id);
        return ApiResponse::success(null, 'Nivel eliminado correctamente');
    }

    public function selectItems()
    {
        $items = $this->levelService->getActiveLevels();
        $items = LevelSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
