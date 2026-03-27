<?php

namespace App\Modules\TeacherPanel\Group\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'enrollmentId' => $this->enrollment_id,
            'status'       => $this->status,
        ];
    }
}
