<?php

namespace App\Modules\TeacherPanel\Group\Http\Resources;

use App\Common\Enums\DayOfWeek;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $daysOfWeek = $this->group_days_of_week
            ? collect(explode(',', $this->group_days_of_week))
                ->map(fn($d) => DayOfWeek::tryFrom(trim($d))?->label())
                ->filter()
                ->values()
                ->toArray()
            : [];

        return [
            'id'              => $this->id,
            'hourlyRate'      => (float) ($this->hourly_rate ?? 0),
            'status'          => $this->status,
            'startDate'       => $this->start_date,
            'endDate'         => $this->end_date,
            'enrollmentsCount' => (int) ($this->enrollments_count ?? 0),
            'group'           => [
                'id'         => $this->group_id,
                'name'       => $this->group_name,
                'level'      => $this->group_level_name,
                'daysOfWeek' => $daysOfWeek,
                'schedule'   => [
                    'shift'     => $this->group_schedule_shift,
                    'startTime' => $this->group_schedule_start_time ? substr($this->group_schedule_start_time, 0, 5) : null,
                    'endTime'   => $this->group_schedule_end_time ? substr($this->group_schedule_end_time, 0, 5) : null,
                ],
                'room'       => $this->group_room_number,
                'branch'     => $this->group_branch_name,
            ],
        ];
    }
}
