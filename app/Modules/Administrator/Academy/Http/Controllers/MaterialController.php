<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use Illuminate\Http\Request;

use App\Common\Http\Responses\ApiResponse;

use App\Modules\Administrator\Academy\Services\MaterialService;

use App\Modules\Administrator\Academy\Http\Requests\Material\MaterialRequest;
use App\Modules\Administrator\Academy\Http\Resources\Material\MaterialDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Material\MaterialSelectItemResource;

class MaterialController
{

    public function __construct(
        private MaterialService $materialService
    ) {}
    public function dataTable(Request $request)
    {

        $items = $this->materialService->dataTable($request);

        $items['data'] = MaterialDataTableItemResource::collection($items['data']);

        return ApiResponse::success($items);
    }

    public function save(MaterialRequest $request)
    {
        $data = $request->validated();
        $this->materialService->save($data);
        return ApiResponse::success($data, 'Material guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->materialService->delete($id);
        return ApiResponse::success(null, 'Material eliminado correctamente');
    }

    public function selectItems()
    {
        $items = $this->materialService->getActiveMaterials();
        $items = MaterialSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
