<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\Barber;

use Illuminate\Http\Resources\Json\JsonResource;

class BarberPaymentSummaryItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fullName' => trim($this->full_name),
            'branchName' => $this->branch_name,
            'commissionPercentage' => (float) $this->commission_percentage,
            'lastPaymentDate' => $this->last_payment_date,
            'totalPaid' => (float) $this->total_paid,
        ];
    }
}
