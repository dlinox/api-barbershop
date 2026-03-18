<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\EmployeeAdvance;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeAdvanceDataTableItemResource extends JsonResource
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
            'infrastructureId' => $this->infrastructure_id,
            'infrastructure' => $this->infrastructure ? [
                'id' => $this->infrastructure->id,
                'name' => $this->infrastructure->name,
            ] : null,
            'cashSessionId' => $this->cash_session_id,
            'cashSession' => $this->cashSession ? [
                'id' => $this->cashSession->id,
            ] : null,
            'paymentMethodId' => $this->payment_method_id,
            'paymentMethod' => $this->paymentMethod ? [
                'id' => $this->paymentMethod->id,
                'name' => $this->paymentMethod->name,
            ] : null,
            'authorizedById' => $this->authorized_by,
            'authorizedBy' => $this->authorizedBy ? [
                'id' => $this->authorizedBy->id,
                'fullName' => $this->authorizedBy->person->full_name ?? null,
            ] : null,
            'paidById' => $this->paid_by,
            'paidBy' => $this->paidBy ? [
                'id' => $this->paidBy->id,
                'fullName' => $this->paidBy->person->full_name ?? null,
            ] : null,
            'amount' => $this->amount,
            'advanceDate' => $this->advance_date?->format('Y-m-d'),
            'paymentReference' => $this->payment_reference,
            'discountedInPaymentId' => $this->discounted_in_payment_id,
            'discountedInPayment' => $this->discountedInPayment ? [
                'id' => $this->discountedInPayment->id,
            ] : null,
            'status' => $this->status,
            'reason' => $this->reason,
            'notes' => $this->notes,
            'createdAt' => $this->created_at?->format('Y-m-d H:i:s'),
            'canEdit' => $this->status !== 'discounted' && !$this->discounted_in_payment_id,
            'canDelete' => $this->status !== 'discounted' && !$this->discounted_in_payment_id,
        ];
    }
}
