<?php

namespace App\Modules\Administrator\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\PurchaseOrderService;
use App\Modules\Administrator\Inventory\Http\Resources\PurchaseOrder\PurchaseOrderDataTableItemResource;

class PurchaseOrderController
{
    public function __construct(
        private PurchaseOrderService $purchaseOrderService
    ) {}

    public function dataTable(Request $request)
    {
        //
    }

    public function save(Request $request)
    {
        //
    }

    public function delete(int $id)
    {
        //
    }

    public function getById(int $id)
    {
        //
    }

    public function updateStatus(int $id, Request $request)
    {
        //
    }

    public function receiveOrder(int $id, Request $request)
    {
        //
    }
}
