<?php

namespace App\Modules\BarberPanel\Pos\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Models\Treasury\CashSession;
use App\Modules\BarberPanel\Pos\Http\Requests\SaleRequest;
use App\Modules\BarberPanel\Pos\Http\Resources\SaleDataTableItemResource;
use App\Modules\BarberPanel\Pos\Http\Resources\SaleDetailResource;
use App\Modules\BarberPanel\Pos\Http\Resources\SaleProductResource;
use App\Modules\BarberPanel\Pos\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosController
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

        $cashSession = CashSession::findOrFail($data['cash_session_id']);
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
