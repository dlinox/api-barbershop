<?php

namespace App\Modules\TeacherPanel\Dashboard\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardSummaryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'activeGroups'   => $this['active_groups'],
            'totalStudents'  => $this['total_students'],
            'attendanceRate' => $this['attendance_rate'],
            'nextClass'      => $this['next_class'],
        ];
    }
}
