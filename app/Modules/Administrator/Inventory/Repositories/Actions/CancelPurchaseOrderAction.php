<?php

namespace App\Modules\Administrator\Inventory\Repositories\Actions;

use App\Models\Inventory\PurchaseOrder;

class CancelPurchaseOrderAction
{
    public function __construct(
        private RegisterKardexMovementAction $registerKardexMovementAction,
    ) {}

    public function execute(PurchaseOrder $order): PurchaseOrder
    {
        // Si la orden ya fue recibida, revertir stock
        if ($order->status === 'received') {
            foreach ($order->items as $item) {
                $this->registerKardexMovementAction->execute([
                    'product_id' => $item->presentation->product_id,
                    'presentation_id' => $item->presentation_id,
                    'infrastructure_id' => $order->infrastructure_id,
                    'movement_type' => 'out',
                    'reason' => 'return',
                    'quantity' => $item->quantity_ordered,
                    'reference_id' => $order->id,
                    'reference_type' => 'inventory_purchase_orders',
                    'notes' => "Anulación de OC #{$order->order_number}",
                ]);
            }
        }

        $order->update(['status' => 'cancelled']);

        return $order->fresh(['items.presentation']);
    }
}
