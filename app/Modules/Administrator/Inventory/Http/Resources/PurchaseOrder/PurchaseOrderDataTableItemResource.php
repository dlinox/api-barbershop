<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\PurchaseOrder;

use App\Models\Core\Infrastructure;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        $infrastructure = Infrastructure::with('infrastructurable')
            ->find($this->infrastructure_id);

        $infrastructureName = $infrastructure?->infrastructurable?->name ?? null;

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
                'id' => $this->supplier_id,
                'name' => $this->supplier_name,
            ],
            'infrastructure' => [
                'id' => $this->infrastructure_id,
                'name' => $infrastructureName,
            ],
            'createdAt' => $this->created_at,
        ];
    }
}
