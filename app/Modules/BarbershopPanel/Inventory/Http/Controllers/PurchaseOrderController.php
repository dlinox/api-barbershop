<?php

namespace App\Modules\BarbershopPanel\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\PurchaseOrderService;
use App\Modules\Administrator\Inventory\Http\Requests\PurchaseOrder\PurchaseOrderReceiveRequest;
use App\Modules\Administrator\Inventory\Http\Resources\PurchaseOrder\PurchaseOrderDataTableItemResource;
use App\Modules\Administrator\Inventory\Http\Resources\PurchaseOrder\PurchaseOrderDetailResource;
use App\Modules\BarbershopPanel\Inventory\Http\Requests\PanelPurchaseOrderRequest;

class PurchaseOrderController
{
    public function __construct(
        private PurchaseOrderService $purchaseOrderService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->purchaseOrderService->dataTable($request);
        $items['data'] = PurchaseOrderDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(PanelPurchaseOrderRequest $request)
    {
        $this->purchaseOrderService->save($request->validated());
        return ApiResponse::success(null, 'Orden de compra guardada correctamente');
    }

    public function getById(int $id)
    {
        $order = $this->purchaseOrderService->getById($id);
        return ApiResponse::success(new PurchaseOrderDetailResource($order));
    }

    public function receiveOrder(int $id, PurchaseOrderReceiveRequest $request)
    {
        $order = $this->purchaseOrderService->receiveOrder($id, $request->validated());
        return ApiResponse::success(new PurchaseOrderDetailResource($order), 'Orden recibida correctamente');
    }

    public function cancelOrder(int $id)
    {
        $order = $this->purchaseOrderService->cancelOrder($id);
        return ApiResponse::success(new PurchaseOrderDetailResource($order), 'Orden cancelada correctamente');
    }
}
