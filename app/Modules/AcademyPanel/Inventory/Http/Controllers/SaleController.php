<?php

namespace App\Modules\AcademyPanel\Inventory\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Http\Requests\Sale\SaleRequest;
use App\Modules\Administrator\Inventory\Http\Resources\Sale\SaleDataTableItemResource;
use App\Modules\Administrator\Inventory\Http\Resources\Sale\SaleDetailResource;
use App\Modules\Administrator\Inventory\Http\Resources\Sale\SaleProductResource;
use App\Modules\Administrator\Inventory\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleController
{
    public function __construct(
        private readonly SaleService $saleService,
    ) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->saleService->dataTable($request);
        $items['data'] = SaleDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getProducts(int $infrastructureId): JsonResponse
    {
        $products = $this->saleService->getProductsWithStock($infrastructureId);
        return ApiResponse::success(SaleProductResource::collection($products));
    }

    public function save(SaleRequest $request): JsonResponse
    {
        $data = $request->validated();

        $cashSession = \App\Models\Treasury\CashSession::findOrFail($data['cash_session_id']);
        $infrastructureId = $cashSession->cashRegister->infrastructure_id;

        $result = $this->saleService->save($data, $infrastructureId);
        return ApiResponse::success($result, 'Venta registrada correctamente');
    }

    public function delete(int $id): JsonResponse
    {
        $this->saleService->delete($id);
        return ApiResponse::success(null, 'Venta eliminada correctamente');
    }

    public function annul(int $id): JsonResponse
    {
        $this->saleService->annul($id);
        return ApiResponse::success(null, 'Venta anulada correctamente');
    }

    public function getById(int $id): JsonResponse
    {
        $sale = $this->saleService->getById($id);
        return ApiResponse::success(new SaleDetailResource($sale));
    }

    public function salesOverview(int $cashSessionId): JsonResponse
    {
        $overview = $this->saleService->salesOverview($cashSessionId);
        return ApiResponse::success($overview);
    }
}
