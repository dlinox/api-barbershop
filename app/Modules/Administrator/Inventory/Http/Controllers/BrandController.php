<?php

namespace App\Modules\Administrator\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\BrandService;
use App\Modules\Administrator\Inventory\Http\Requests\Brand\BrandRequest;
use App\Modules\Administrator\Inventory\Http\Resources\Brand\BrandDataTableItemResource;
use App\Modules\Administrator\Inventory\Http\Resources\Brand\BrandSelectItemResource;

class BrandController
{
    public function __construct(
        private BrandService $brandService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->brandService->dataTable($request);
        $items['data'] = BrandDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(BrandRequest $request)
    {
        $data = $request->validated();
        $this->brandService->save($data);
        return ApiResponse::success($data, 'Marca guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->brandService->delete($id);
        return ApiResponse::success(null, 'Marca eliminada correctamente');
    }

    public function selectItems()
    {
        $items = $this->brandService->getActiveBrands();
        $items = BrandSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
