<?php

namespace App\Modules\Administrator\Academy\Http\Resources\EnrollmentPayment;

use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentPaymentHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'total' => $this->total,
            'receiptSerie' => $this->receipt_serie,
            'receiptNumber' => $this->receipt_number,
            'observations' => $this->observations,
            'transactionDate' => $this->transaction_date,
            'status' => $this->status
        ];
    }
}
