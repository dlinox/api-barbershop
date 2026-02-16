<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Attendance;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class GroupAttendanceItemResource extends JsonResource
{
    public function toArray($request)
    {
        $daysNumber = explode(',', $this->days_of_week);
        $daysOfWeek = collect($daysNumber)->map(function ($day) {
            return Carbon::now()->startOfWeek(Carbon::SUNDAY)->addDays((int)$day)->locale('es')->shortDayName;
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'startDate' => Carbon::parse($this->start_date)->format('d-m-Y'),
            'endDate' => Carbon::parse($this->end_date)->format('d-m-Y'),
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
                'startTime' => Carbon::parse($this->schedule_start_time)->format('g:i A'), //08:00 am
                'endTime' => Carbon::parse($this->schedule_end_time)->format('g:i A'), //05:00 pm
            ],
            'attendanceDeadlineStatus' => $this->attendance_deadline_status,
        ];
    }
}
