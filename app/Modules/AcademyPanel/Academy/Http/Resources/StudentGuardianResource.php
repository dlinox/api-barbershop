<?php

namespace App\Modules\AcademyPanel\Academy\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentGuardianResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'studentId' => $this->student_id,
            'fullName'  => $this->full_name,
            'kinship'   => $this->kinship,
            'phone'     => $this->phone,
        ];
    }
}
