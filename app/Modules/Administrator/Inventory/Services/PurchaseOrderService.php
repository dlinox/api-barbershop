<?php

namespace App\Modules\Administrator\Inventory\Services;

use App\Modules\Administrator\Inventory\Repositories\PurchaseOrderRepository;
use Illuminate\Http\Request;

class PurchaseOrderService
{
    public function __construct(
        private PurchaseOrderRepository $purchaseOrderRepository
    ) {}

    public function dataTable(Request $request)
    {
        //
    }

    public function save(array $data)
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

    public function updateStatus(int $id, string $status)
    {
        //
    }

    public function receiveOrder(int $id, array $data)
    {
        //
    }
}
