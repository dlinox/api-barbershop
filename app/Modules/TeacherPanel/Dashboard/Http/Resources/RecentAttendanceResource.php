<?php

namespace App\Modules\TeacherPanel\Dashboard\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecentAttendanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'      => $this['id'],
            'date'    => $this['date'],
            'group'   => $this['group'],
            'checkIn' => $this['check_in'],
            'status'  => $this['status'],
        ];
    }
}
