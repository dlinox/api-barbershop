<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\PurchaseOrder;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'orderNumber' => $this->order_number,
            'receiptType' => $this->receipt_type,
            'receiptSerie' => $this->receipt_serie,
            'receiptNumber' => $this->receipt_number,
            'status' => $this->status,
            'orderDate' => $this->order_date,
            'expectedDate' => $this->expected_date,
            'receivedDate' => $this->received_date,
            'totalAmount' => (float) $this->total_amount,
            'notes' => $this->notes,
            'supplier' => [
                'id' => $this->supplier?->id,
                'name' => $this->supplier?->name,
            ],
            'infrastructure' => [
                'id' => $this->infrastructure?->id,
                'name' => $this->infrastructure?->infrastructurable?->name,
            ],
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'presentationId' => $item->presentation_id,
                    'presentationName' => $item->presentation?->name,
                    'presentationSku' => $item->presentation?->sku,
                    'productName' => $item->presentation?->product?->name,
                    'quantityOrdered' => (int) $item->quantity_ordered,
                    'unitPrice' => (float) $item->unit_price,
                    'subtotal' => (float) $item->subtotal,
                ];
            }),
            'createdAt' => $this->created_at,
        ];
    }
}
