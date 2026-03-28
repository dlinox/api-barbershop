<?php

namespace App\Modules\BarberPanel\Payment\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'period'      => $this->period,
            'periodStart' => $this->period_start?->format('Y-m-d'),
            'periodEnd'   => $this->period_end?->format('Y-m-d'),
            'baseAmount'  => (float) $this->base_amount,
            'bonus'       => (float) $this->bonus,
            'deductions'  => (float) $this->deductions,
            'totalAmount' => (float) $this->total_amount,
            'status'      => $this->status,
            'paymentDate' => $this->payment_date?->format('Y-m-d'),
        ];
    }
}
