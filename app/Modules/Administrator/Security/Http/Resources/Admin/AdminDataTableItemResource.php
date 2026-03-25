<?php

namespace App\Modules\Administrator\Security\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->core_person_id,
            'infrastructures' => $this->infrastructures->pluck('id')->toArray(),
            'person' => [
                'id' => $this->core_person_id,
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
            'user' => [
                'id' => $this->user_id,
                'username' => $this->user_username,
                'email' => $this->user_email,
                'isActive' => (bool) $this->user_is_active,
            ],
            'role' => [
                'id' => $this->role_id,
            ],
        ];
    }
}
