<?php

namespace App\Modules\TeacherPanel\Group\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupStudentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'enrollmentId'    => $this->enrollment_id,
            'name'            => $this->name,
            'paternalSurname' => $this->paternal_surname,
            'maternalSurname' => $this->maternal_surname,
        ];
    }
}
