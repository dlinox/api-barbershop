<?php

namespace App\Modules\StudentPanel\Attendance\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'checkIn'     => $this->check_in,
            'checkOut'    => $this->check_out,
            'observation' => $this->observation,
            'status'      => $this->status,
            'date'        => $this->attendance_deadline_date,
            'group'       => [
                'id'       => $this->group_id,
                'name'     => $this->group_name,
                'level'    => $this->group_level_name,
                'schedule' => [
                    'shift'     => $this->group_schedule_shift,
                    'startTime' => $this->group_schedule_start_time ? substr($this->group_schedule_start_time, 0, 5) : null,
                    'endTime'   => $this->group_schedule_end_time ? substr($this->group_schedule_end_time, 0, 5) : null,
                ],
            ],
        ];
    }
}
