<?php

namespace App\Modules\Administrator\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\PurchaseOrderService;
use App\Modules\Administrator\Inventory\Http\Requests\PurchaseOrder\PurchaseOrderRequest;
use App\Modules\Administrator\Inventory\Http\Requests\PurchaseOrder\PurchaseOrderReceiveRequest;
use App\Modules\Administrator\Inventory\Http\Resources\PurchaseOrder\PurchaseOrderDataTableItemResource;
use App\Modules\Administrator\Inventory\Http\Resources\PurchaseOrder\PurchaseOrderDetailResource;

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

    public function save(PurchaseOrderRequest $request)
    {
        $data = $request->validated();
        $this->purchaseOrderService->save($data);
        return ApiResponse::success(null, 'Orden de compra guardada correctamente');
    }

    public function getById(int $id)
    {
        $order = $this->purchaseOrderService->getById($id);
        $order = new PurchaseOrderDetailResource($order);
        return ApiResponse::success($order);
    }

    public function receiveOrder(int $id, PurchaseOrderReceiveRequest $request)
    {
        $data = $request->validated();
        $order = $this->purchaseOrderService->receiveOrder($id, $data);
        $order = new PurchaseOrderDetailResource($order);
        return ApiResponse::success($order, 'Orden recibida correctamente');
    }

    public function cancelOrder(int $id)
    {
        $order = $this->purchaseOrderService->cancelOrder($id);
        $order = new PurchaseOrderDetailResource($order);
        return ApiResponse::success($order, 'Orden cancelada correctamente');
    }
}
