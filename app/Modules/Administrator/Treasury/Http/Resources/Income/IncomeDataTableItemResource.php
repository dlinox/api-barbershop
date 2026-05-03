<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\Income;

use Illuminate\Http\Resources\Json\JsonResource;

class IncomeDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $clientName = $this->person_name
            ? collect([$this->person_name, $this->person_paternal_surname])->filter()->implode(' ')
            : 'Público General';

        return [
            'id'              => $this->id,
            'receiptType'     => $this->receipt_type,
            'receiptSerie'    => $this->receipt_serie,
            'receiptNumber'   => $this->receipt_number,
            'clientName'      => $clientName,
            'clientDoc'       => $this->person_document_number ?? '-',
            'userName'        => $this->user_username ?? '',
            'subtotal'        => (float) $this->subtotal,
            'discount'        => (float) $this->discount,
            'tax'             => (float) $this->tax,
            'total'           => (float) $this->total,
            'status'          => $this->status,
            'isEdited'        => (bool) $this->is_edited,
            'transactionDate' => $this->transaction_date?->format('Y-m-d'),
            'createdAt'       => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
