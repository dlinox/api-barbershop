<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\Worker;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'position' => $this->position,
            'monthlySalary' => (float) $this->monthly_salary,
            'paymentFrequency' => $this->payment_frequency,
            'isActive' => (bool) $this->is_active,
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
