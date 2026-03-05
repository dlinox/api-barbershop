<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Sale;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'clientName' => $this->person ? $this->person->name . ' ' . $this->person->last_name : 'Público General',
            'clientDoc' => $this->person ? $this->person->document_number : '-',
            'userName' => $this->user?->username ?? '',
            'context' => $this->context,
            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'total' => (float) $this->total,
            'status' => $this->status,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
