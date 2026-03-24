<?php

namespace App\Modules\Administrator\Inventory\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\SaleService;
use App\Modules\Administrator\Inventory\Http\Requests\Sale\SaleRequest;
use App\Modules\Administrator\Inventory\Http\Resources\Sale\SaleProductResource;
use App\Modules\Administrator\Inventory\Http\Resources\Sale\SaleDataTableItemResource;
use App\Modules\Administrator\Inventory\Http\Resources\Sale\SaleDetailResource;
use Illuminate\Http\Request;

class SaleController
{
    public function __construct(
        private SaleService $saleService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->saleService->dataTable($request);
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

        $result = $this->saleService->save($data, $infrastructureId);
        return ApiResponse::success($result, 'Venta registrada correctamente');
    }

    public function delete(int $id)
    {
        $this->saleService->delete($id);
        return ApiResponse::success(null, 'Venta eliminada correctamente');
    }

    public function annul(int $id)
    {
        $this->saleService->annul($id);
        return ApiResponse::success(null, 'Venta anulada correctamente');
    }

    public function getById(int $id)
    {
        $sale = $this->saleService->getById($id);
        $sale = new SaleDetailResource($sale);
        return ApiResponse::success($sale);
    }

    public function salesOverview(int $cashSessionId)
    {
        $overview = $this->saleService->salesOverview($cashSessionId);
        return ApiResponse::success($overview);
    }
}
