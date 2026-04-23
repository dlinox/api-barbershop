<?php

namespace App\Modules\BarbershopPanel\Inventory\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\ProductPresentationService;
use App\Modules\Administrator\Inventory\Http\Requests\Product\ProductPresentationRequest;

class ProductPresentationController
{
    public function __construct(
        private ProductPresentationService $productPresentationService
    ) {}

    public function save(ProductPresentationRequest $request)
    {
        $this->productPresentationService->save($request->validated());
        return ApiResponse::success(null, 'Presentación guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->productPresentationService->delete($id);
        return ApiResponse::success(null, 'Presentación eliminada correctamente');
    }
}
