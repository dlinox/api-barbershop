<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'branchId' => $this->branch_id,
            'paymentType' => $this->payment_type,
            'monthlySalary' => $this->monthly_salary ? (float) $this->monthly_salary : null,
            'isActive' => (bool) $this->is_active,
            'branch' => $this->branch_id ? [
                'id' => $this->branch_id,
                'name' => $this->branch_name,
            ] : null,
            'person' => [
                'id' => $this->id,
                'documentType' => $this->person_document_type,
                'documentNumber' => $this->person_document_number,
                'name' => $this->person_name,
                'paternalSurname' => $this->person_paternal_surname,
                'maternalSurname' => $this->person_maternal_surname,
                'email' => $this->person_email,
                'phone' => $this->person_phone,
                'dateBirth' => $this->person_date_birth,
                'gender' => $this->person_gender,
                'address' => $this->person_address,
            ],
        ];
    }
}
