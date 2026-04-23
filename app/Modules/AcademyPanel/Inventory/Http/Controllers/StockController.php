<?php

namespace App\Modules\AcademyPanel\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Context\AdminContext;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\StockService;
use App\Modules\Administrator\Inventory\Http\Resources\Stock\StockDataTableItemResource;
use App\Modules\AcademyPanel\Inventory\Http\Requests\PanelInitializeStockRequest;
use App\Modules\AcademyPanel\Inventory\Http\Requests\PanelAdjustStockRequest;

class StockController
{
    public function __construct(
        private StockService $stockService
    ) {}

    public function dataTable(Request $request)
    {
        $infrastructureId = AdminContext::infrastructureId();
        $items = $this->stockService->dataTable($request, $infrastructureId);
        $items['data'] = StockDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function initializeStock(PanelInitializeStockRequest $request)
    {
        $this->stockService->initializeStock($request->validated());
        return ApiResponse::success(null, 'Stock inicializado correctamente');
    }

    public function adjustStock(PanelAdjustStockRequest $request)
    {
        $this->stockService->adjustStock($request->validated());
        return ApiResponse::success(null, 'Stock ajustado correctamente');
    }
}
