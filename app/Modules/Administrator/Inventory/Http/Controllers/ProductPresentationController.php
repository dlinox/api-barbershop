<?php

namespace App\Modules\Administrator\Inventory\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\ProductPresentationService;
use App\Modules\Administrator\Inventory\Http\Requests\Product\ProductPresentationRequest;
use App\Modules\Administrator\Inventory\Http\Resources\Product\ProductPresentationSelectItemResource;

class ProductPresentationController
{
    public function __construct(
        private ProductPresentationService $productPresentationService
    ) {}

    public function save(ProductPresentationRequest $request)
    {
        $data = $request->validated();
        $this->productPresentationService->save($data);
        return ApiResponse::success($data, 'Presentación guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->productPresentationService->delete($id);
        return ApiResponse::success(null, 'Presentación eliminada correctamente');
    }

    public function selectItems(int $productId)
    {
        $items = $this->productPresentationService->getByProduct($productId);
        $items = ProductPresentationSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
