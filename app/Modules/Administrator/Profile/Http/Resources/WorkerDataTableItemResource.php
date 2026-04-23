<?php

namespace App\Modules\Administrator\Profile\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                 => $this->id,
            'infrastructureId'   => $this->infrastructure_id,
            'position'           => $this->position,
            'monthlySalary'      => $this->monthly_salary !== null ? (float) $this->monthly_salary : null,
            'paymentFrequency'   => $this->payment_frequency,
            'isActive'           => (bool) $this->is_active,
            'person' => [
                'id'              => $this->id,
                'documentType'    => $this->person_document_type,
                'documentNumber'  => $this->person_document_number,
                'name'            => $this->person_name,
                'paternalSurname' => $this->person_paternal_surname,
                'maternalSurname' => $this->person_maternal_surname,
                'email'           => $this->person_email,
                'phone'           => $this->person_phone,
            ],
        ];
    }
}
