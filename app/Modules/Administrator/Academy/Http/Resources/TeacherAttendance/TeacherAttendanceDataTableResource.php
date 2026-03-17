<?php

namespace App\Modules\Administrator\Academy\Http\Resources\TeacherAttendance;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherAttendanceDataTableResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'groupTeacherId' => $this->group_teacher_id,
            'teacher' => [
                'id' => $this->teacher_id,
                'personId' => $this->teacher_person_id,
                'name' => $this->teacher_name,
                'paternalSurname' => $this->teacher_paternal_surname,
                'maternalSurname' => $this->teacher_maternal_surname,
                'document' => $this->teacher_document,
            ],
            'group' => [
                'id' => $this->group_id,
                'name' => $this->group_name,
                'startDate' => $this->group_start_date,
                'endDate' => $this->group_end_date,
            ],
            'level' => [
                'id' => $this->level_id,
                'name' => $this->level_name,
            ],
            'schedule' => [
                'id' => $this->schedule_id,
                'shift' => $this->schedule_shift,
                'startTime' => $this->schedule_start_time,
                'endTime' => $this->schedule_end_time,
            ],
            'assignment' => [
                'hourlyRate' => $this->hourly_rate,
                'holidayHourlyRate' => $this->holiday_hourly_rate,
                'status' => $this->assignment_status,
            ],
            'attendance' => [
                'id' => $this->attendance_id,
                'date' => $this->attendance_date,
                'checkIn' => $this->check_in,
                'checkOut' => $this->check_out,
                'checkToken' => $this->check_token,
                'checkType' => $this->check_type,
                'status' => $this->attendance_status,
                'observation' => $this->observation,
            ],
        ];
    }
}
