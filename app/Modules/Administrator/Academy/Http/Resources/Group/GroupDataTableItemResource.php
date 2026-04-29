<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Group;

use App\Common\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'enrollmentPrice' => (float) $this->enrollment_price,
            'monthlyPrice' => (float) $this->monthly_price,
            'daysOfWeek' => explode(',', $this->days_of_week),
            'attendanceToleranceMinutes' => (int) $this->attendance_tolerance_minutes,
            'status' => $this->status,
            'branch' => [
                'id' => $this->branch_id,
                'name' => $this->branch_name,
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
            'room' => [
                'id' => $this->room_id,
                'number' => $this->room_number,
                'floor' => $this->room_floor,
            ],
            'teachers' => $this->groupTeachers->map(function ($gt) {
                return [
                    'id' => $gt->id,
                    'teacherId' => $gt->teacher_id,
                    'hourlyRate' => (float) $gt->hourly_rate,
                    'holidayHourlyRate' => (float) $gt->holiday_hourly_rate,
                    'status' => $gt->status,
                    'startDate' => $gt->start_date,
                    'endDate' => $gt->end_date,
                    'observation' => $gt->observation,
                    'person' => [
                        'name' => $gt->person_name,
                        'paternalSurname' => $gt->person_paternal_surname,
                        'maternalSurname' => $gt->person_maternal_surname,
                    ],
                ];
            }),
            'paymentPlans' => $this->paymentPlans()->where('type', '!=', 'enrollment')->get()->map(function ($paymentPlan) {
                return [
                    'id' => $paymentPlan->id,
                    'startDate' => $paymentPlan->start_date,
                    'endDate' => $paymentPlan->end_date,
                    'amount' => (float) $paymentPlan->amount,
                ];
            }),
            'enrollmentsCount' => $this->enrollments_count ?? 0,
        ];
    }
}
