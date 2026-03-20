<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'isActive' => (bool) $this->is_active,
            'enrollmentsCount' => $this->enrollments_count ?? 0,
            'person' => [
                'id' => $this->id,
                'documentType' => $this->person_document_type,
                'documentNumber' => $this->person_document_number,
                'name' => $this->person_name,
                'paternalSurname' => $this->person_paternal_surname,
                'maternalSurname' => $this->person_maternal_surname,
                'email' => $this->person_email,
                'phone' => $this->person_phone,
            ],
            'user' => [
                'id' => $this->user_id,
                'username' => $this->user_username,
                'email' => $this->user_email,
                'isActive' => (bool) $this->user_is_active,
            ],
        ];
    }
}
