<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentUserDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'person' => [
                'name' => $this->person_name,
                'paternalSurname' => $this->person_paternal_surname,
                'maternalSurname' => $this->person_maternal_surname,
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
