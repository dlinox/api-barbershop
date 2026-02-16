<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->person_name . ' ' . $this->person_paternal_surname . ' ' . $this->person_maternal_surname . ' (' . $this->person_document_number . ')',
        ];
    }
}
