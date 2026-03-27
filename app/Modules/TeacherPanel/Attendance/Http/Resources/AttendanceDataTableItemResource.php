<?php

namespace App\Modules\TeacherPanel\Attendance\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'date'        => $this->date?->format('Y-m-d'),
            'checkIn'     => $this->check_in ? substr($this->check_in, 0, 5) : null,
            'checkOut'    => $this->check_out ? substr($this->check_out, 0, 5) : null,
            'status'      => $this->status,
            'observation' => $this->observation,
            'group'       => [
                'id'       => $this->group_id,
                'name'     => $this->group_name,
                'schedule' => [
                    'shift'     => $this->group_schedule_shift,
                    'startTime' => $this->group_schedule_start_time ? substr($this->group_schedule_start_time, 0, 5) : null,
                    'endTime'   => $this->group_schedule_end_time ? substr($this->group_schedule_end_time, 0, 5) : null,
                ],
            ],
        ];
    }
}
