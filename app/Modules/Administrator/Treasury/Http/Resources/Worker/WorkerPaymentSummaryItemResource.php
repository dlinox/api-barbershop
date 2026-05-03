<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\Worker;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerPaymentSummaryItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'fullName'         => trim($this->full_name),
            'position'         => $this->position,
            'monthlySalary'    => (float) $this->monthly_salary,
            'infrastructureId' => $this->infrastructure_id,
            'infrastructureName' => $this->infrastructure_name,
            'lastPaymentDate'  => $this->last_payment_date,
            'totalPaid'        => (float) $this->total_paid,
        ];
    }
}
