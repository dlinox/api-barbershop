<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Attendance;

use App\Common\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupAttendanceItemResource extends JsonResource
{
    public function toArray($request)
    {
        $daysOfWeek = DateHelper::getDayNamesFromCsv($this->days_of_week);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'startDate' => DateHelper::formatDate($this->start_date),
            'endDate' => DateHelper::formatDate($this->end_date),
            'daysOfWeek' =>  $daysOfWeek,
            'enrollmentPrice' => $this->enrollment_price,
            'monthlyPrice' => $this->monthly_price,
            'branch' => [
                'name' => $this->branch_name,
            ],
            'room' => [
                'number' => $this->room_number,
            ],
            'level' => [
                'name' => $this->level_name,
            ],
            'schedule' => [
                'shift' => $this->schedule_shift,
                'startTime' => DateHelper::formatTime($this->schedule_start_time),
                'endTime' => DateHelper::formatTime($this->schedule_end_time),
            ],
            'attendanceDeadlineStatus' => $this->attendance_deadline_status,
        ];
    }
}
