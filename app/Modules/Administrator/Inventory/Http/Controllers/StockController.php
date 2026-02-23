<?php

namespace App\Modules\Administrator\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\StockService;
use App\Modules\Administrator\Inventory\Http\Requests\Stock\InitializeStockRequest;
use App\Modules\Administrator\Inventory\Http\Requests\Stock\AdjustStockRequest;
use App\Modules\Administrator\Inventory\Http\Resources\Stock\StockDataTableItemResource;

class StockController
{
    public function __construct(
        private StockService $stockService
    ) {}

    public function dataTable(Request $request, $infrastructureId)
    {
        $items = $this->stockService->dataTable($request, $infrastructureId);
        $items['data'] = StockDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function initializeStock(InitializeStockRequest $request)
    {
        $data = $request->validated();
        $this->stockService->initializeStock($data);
        return ApiResponse::success(null, 'Stock inicializado correctamente');
    }

    public function adjustStock(AdjustStockRequest $request)
    {
        $data = $request->validated();
        $this->stockService->adjustStock($data);
        return ApiResponse::success(null, 'Stock ajustado correctamente');
    }
}
