<?php

namespace App\Modules\StudentPanel\Payment\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'       => $this->id,
            'type'     => $this->type,
            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'total'    => (float) $this->total,
            'group'    => [
                'name'      => $this->group_name,
                'levelName' => $this->group_level_name,
            ],
            'plan' => [
                'startDate' => $this->plan_start_date
                    ? Carbon::parse($this->plan_start_date)->locale('es')->isoFormat('D \d\e MMM')
                    : null,
                'endDate' => $this->plan_end_date
                    ? Carbon::parse($this->plan_end_date)->locale('es')->isoFormat('D \d\e MMM')
                    : null,
            ],
            'income' => [
                'id'            => $this->income_id,
                'receiptSerie'  => $this->receipt_serie,
                'receiptNumber' => $this->receipt_number,
                'status'        => $this->income_status,
            ],
            'transactionDate' => $this->transaction_date,
            'createdAt'       => Carbon::parse($this->created_at)->format('d/m/Y'),
        ];
    }
}
