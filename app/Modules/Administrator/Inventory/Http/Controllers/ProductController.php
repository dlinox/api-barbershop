<?php

namespace App\Modules\Administrator\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\ProductService;
use App\Modules\Administrator\Inventory\Http\Requests\Product\ProductRequest;
use App\Modules\Administrator\Inventory\Http\Resources\Product\ProductDataTableItemResource;
use App\Modules\Administrator\Inventory\Http\Resources\Product\ProductSelectItemResource;

class ProductController
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->productService->dataTable($request);
        $items['data'] = ProductDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(ProductRequest $request)
    {
        $data = $request->validated();
        $this->productService->save($data);
        return ApiResponse::success($data, 'Producto guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->productService->delete($id);
        return ApiResponse::success(null, 'Producto eliminado correctamente');
    }

    public function selectItems()
    {
        $items = $this->productService->getActiveProducts();
        $items = ProductSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
