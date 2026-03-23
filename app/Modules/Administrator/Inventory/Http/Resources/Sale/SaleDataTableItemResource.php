<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Sale;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $clientName = $this->person_name
            ? trim("{$this->person_name} {$this->person_paternal_surname}")
            : 'Público General';

        return [
            'id' => $this->id,
            'clientName' => $clientName,
            'clientDoc' => $this->person_document_number ?? '-',
            'userName' => $this->user_username ?? '',
            'context' => $this->context,
            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'total' => (float) $this->total,
            'status' => $this->status,
            'incomeId' => $this->income_id,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
