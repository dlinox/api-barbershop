<?php

namespace App\Modules\Administrator\Academy\Http\Resources\StudentGuardian;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentGuardianResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'studentId' => $this->student_id,
            'fullName' => $this->full_name,
            'kinship' => $this->kinship,
            'phone' => $this->phone,
        ];
    }
}
