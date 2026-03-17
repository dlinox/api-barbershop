<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\Income;

use Illuminate\Http\Resources\Json\JsonResource;

class IncomeDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                  => $this->id,
            'receiptType'         => $this->receipt_type,
            'receiptSerie'        => $this->receipt_serie,
            'receiptNumber'       => $this->receipt_number,
            'observations'        => $this->observations,
            'subtotal'            => (float) $this->subtotal,
            'discount'            => (float) $this->discount,
            'tax'                 => (float) $this->tax,
            'total'               => (float) $this->total,
            'status'              => $this->status,
            'transactionDate'     => $this->transaction_date?->format('Y-m-d'),
            'transactionableType' => $this->transactionable_type,
            'transactionableId'   => $this->transactionable_id,
            'client'              => $this->person ? [
                'id'             => $this->person->id,
                'name'           => trim("{$this->person->name} {$this->person->paternal_surname} {$this->person->maternal_surname}"),
                'documentNumber' => $this->person->document_number,
            ] : null,
            'details'        => $this->details->map(fn($d) => [
                'id'          => $d->id,
                'description' => $d->description,
                'quantity'    => (int) $d->quantity,
                'unitPrice'   => (float) $d->unit_price,
                'discount'    => (float) $d->discount,
                'subtotal'    => (float) $d->subtotal,
            ]),
            'paymentMethods' => $this->paymentMethods->map(fn($pm) => [
                'id'               => $pm->id,
                'paymentMethodId'  => $pm->payment_method_id,
                'paymentMethod'    => $pm->paymentMethod?->name,
                'amount'           => (float) $pm->amount,
                'paymentReference' => $pm->payment_reference,
            ]),
        ];
    }
}
