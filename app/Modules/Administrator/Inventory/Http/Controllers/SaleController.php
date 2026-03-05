<?php

namespace App\Modules\Administrator\Inventory\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\SaleService;
use App\Modules\Administrator\Inventory\Http\Requests\Sale\SaleRequest;
use App\Modules\Administrator\Inventory\Http\Resources\Sale\SaleProductResource;
use App\Modules\Administrator\Inventory\Http\Resources\Sale\SaleDataTableItemResource;

class SaleController
{
    public function __construct(
        private SaleService $saleService
    ) {}

    public function dataTable(\Illuminate\Http\Request $request, int $cashRegisterId)
    {
        $items = $this->saleService->dataTable($request, $cashRegisterId);
        $items['data'] = SaleDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getProducts(int $infrastructureId)
    {
        $products = $this->saleService->getProductsWithStock($infrastructureId);
        $products = SaleProductResource::collection($products);
        return ApiResponse::success($products);
    }

    public function save(SaleRequest $request)
    {
        $data = $request->validated();

        // infrastructureId viene de la sesión de caja
        $cashSession = \App\Models\Treasury\CashSession::findOrFail($data['cash_session_id']);
        $infrastructureId = $cashSession->cashRegister->infrastructure_id;

        $this->saleService->save($data, $infrastructureId);
        return ApiResponse::success(null, 'Venta registrada correctamente');
    }
}
