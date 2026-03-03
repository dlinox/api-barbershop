<?php

namespace App\Modules\Administrator\Inventory\Repositories\Actions;

use App\Models\Inventory\PurchaseOrder;

class ReceivePurchaseOrderAction
{
    public function __construct(
        private RegisterKardexMovementAction $registerKardexMovementAction,
    ) {}

    public function execute(PurchaseOrder $order, array $data): PurchaseOrder
    {
        // Registrar entrada en kardex por cada ítem
        foreach ($order->items as $item) {
            $this->registerKardexMovementAction->execute([
                'product_id' => $item->presentation->product_id,
                'presentation_id' => $item->presentation_id,
                'infrastructure_id' => $order->infrastructure_id,
                'movement_type' => 'in',
                'reason' => 'purchase',
                'quantity' => $item->quantity_ordered,
                'unit_cost' => $item->unit_price,
                'reference_id' => $order->id,
                'reference_type' => 'inventory_purchase_orders',
                'notes' => "Recepción de OC #{$order->order_number}",
            ]);
        }

        // Actualizar orden con datos de comprobante y estado
        $order->update([
            'status' => 'received',
            'received_date' => now(),
            'receipt_type' => $data['receipt_type'] ?? $order->receipt_type,
            'receipt_serie' => $data['receipt_serie'] ?? $order->receipt_serie,
            'receipt_number' => $data['receipt_number'] ?? $order->receipt_number,
        ]);

        return $order->fresh(['items.presentation']);
    }
}
