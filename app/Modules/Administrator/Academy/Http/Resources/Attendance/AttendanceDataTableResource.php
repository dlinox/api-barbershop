<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Attendance;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceDataTableResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'checkIn' => $this->check_in,
            'checkOut' => $this->check_out,
            'observation' => $this->observation,
            'status' => $this->status,
            'attendanceDeadline' => [
                'id' => $this->attendance_deadline_id,
                'date' => $this->attendance_deadline_date,
            ],
            'enrollment' => [
                'id' => $this->enrollment_id,
                'student' => [
                    'id' => $this->enrollment_student_id,
                    'name' => $this->enrollment_student_name,
                    'surname' => $this->enrollment_student_paternal_surname,
                ],
            ],
            'group' => [
                'id' => $this->group_id,
                'name' => $this->group_name,
                'level' => [
                    'id' => $this->group_level_id,
                    'name' => $this->group_level_name,
                ],
                'schedule' => [
                    'id' => $this->group_schedule_id,
                    'shift' => $this->group_schedule_shift,
                    'startTime' => $this->group_schedule_start_time,
                    'endTime' => $this->group_schedule_end_time,
                ],
            ],

        ];
    }
}
