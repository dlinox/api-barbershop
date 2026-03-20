<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\EmployeePayment;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeePaymentDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employeeType' => $this->employee_type,
            'employeeId' => $this->employee_id,
            'employee' => $this->employee ? [
                'id' => $this->employee->id,
                'fullName' => $this->employee->person->full_name ?? null,
            ] : null,
            'branch' => ($this->employee && $this->employee->relationLoaded('branch') && $this->employee->branch) ? [
                'id' => $this->employee->branch->id,
                'name' => $this->employee->branch->name,
            ] : null,
            'paymentMethodId' => $this->payment_method_id,
            'paymentMethod' => $this->paymentMethod ? [
                'id' => $this->paymentMethod->id,
                'name' => $this->paymentMethod->name,
            ] : null,
            'paidById' => $this->paid_by,
            'paidBy' => $this->paidBy ? [
                'id' => $this->paidBy->id,
                'fullName' => $this->paidBy->person->full_name ?? null,
            ] : null,
            'period' => $this->period,
            'periodStart' => $this->period_start?->format('Y-m-d'),
            'periodEnd' => $this->period_end?->format('Y-m-d'),
            'baseAmount' => (float) $this->base_amount,
            'bonus' => (float) $this->bonus,
            'deductions' => (float) $this->deductions,
            'totalAmount' => (float) $this->total_amount,
            'paymentDate' => $this->payment_date?->format('Y-m-d'),
            'paymentReference' => $this->payment_reference,
            'status' => $this->status,
            'notes' => $this->notes,
            'createdAt' => $this->created_at?->format('Y-m-d H:i:s'),
            'canEdit' => $this->status !== 'cancelled',
            'canDelete' => $this->status !== 'cancelled',
        ];
    }
}
