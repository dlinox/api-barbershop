<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
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
