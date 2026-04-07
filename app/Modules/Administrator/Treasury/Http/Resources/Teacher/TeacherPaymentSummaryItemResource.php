<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherPaymentSummaryItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fullName' => trim($this->full_name),
            'branchName' => $this->branch_name,
            'paymentType' => $this->payment_type,
            'monthlySalary' => (float) $this->monthly_salary,
            'totalGroups' => (int) $this->total_groups,
            'lastPaymentDate' => $this->last_payment_date,
            'totalPaid' => (float) $this->total_paid,
        ];
    }
}
