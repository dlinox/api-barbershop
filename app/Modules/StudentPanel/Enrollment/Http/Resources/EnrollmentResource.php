<?php

namespace App\Modules\StudentPanel\Enrollment\Http\Resources;

use App\Common\Enums\DayOfWeek;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray($request): array
    {
        $group = $this->group;
        $schedule = $group?->schedule;
        $teacher = $group?->groupTeachers->first()?->teacher?->person;

        $daysOfWeek = $group?->days_of_week
            ? collect(explode(',', $group->days_of_week))
                ->map(fn($d) => DayOfWeek::tryFrom(trim($d))?->label())
                ->filter()
                ->values()
                ->toArray()
            : [];

        return [
            'id'     => $this->id,
            'date'   => $this->date,
            'status' => $this->status,
            'group'  => [
                'name'            => $group?->name,
                'level'           => $group?->level?->name,
                'schedule'        => [
                    'shift'     => $schedule?->shift,
                    'startTime' => $schedule ? substr($schedule->start_time, 0, 5) : null,
                    'endTime'   => $schedule ? substr($schedule->end_time, 0, 5) : null,
                ],
                'room'            => $group?->room?->name,
                'branch'          => $group?->branch?->name,
                'daysOfWeek'      => $daysOfWeek,
                'teacher'         => $teacher ? trim($teacher->name . ' ' . $teacher->paternal_surname) : null,
                'startDate'       => $group?->start_date,
                'endDate'         => $group?->end_date,
                'enrollmentPrice' => (float) ($group?->enrollment_price ?? 0),
                'monthlyPrice'    => (float) ($group?->monthly_price ?? 0),
            ],
        ];
    }
}
