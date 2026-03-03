<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\PurchaseOrder;

class PurchaseOrderRepository
{
    public function dataTable($request)
    {
        $query = PurchaseOrder::select(
            'inventory_purchase_orders.id',
            'inventory_purchase_orders.supplier_id',
            'inventory_purchase_orders.infrastructure_id',
            'inventory_purchase_orders.order_number',
            'inventory_purchase_orders.receipt_type',
            'inventory_purchase_orders.receipt_serie',
            'inventory_purchase_orders.receipt_number',
            'inventory_purchase_orders.status',
            'inventory_purchase_orders.order_date',
            'inventory_purchase_orders.expected_date',
            'inventory_purchase_orders.received_date',
            'inventory_purchase_orders.total_amount',
            'inventory_purchase_orders.notes',
            'inventory_purchase_orders.created_at',

            // proveedor
            'inventory_suppliers.name as supplier_name',
        )
            ->join('inventory_suppliers', 'inventory_purchase_orders.supplier_id', '=', 'inventory_suppliers.id');

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return PurchaseOrder::updateOrCreate(
            ['id' => $data['id'] ?? null],
            $data
        );
    }

    public function getById(int $id)
    {
        return PurchaseOrder::with(['items.presentation', 'supplier', 'infrastructure.infrastructurable'])
            ->findOrFail($id);
    }
}
