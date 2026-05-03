<?php

namespace App\Modules\Administrator\Academy\Repositories\Queries;

use App\Models\Profile\Teacher;
use App\Models\Academy\GroupTeacher;
use App\Models\Academy\TeacherAttendance;
use App\Models\Treasury\EmployeeAdvance;
use App\Models\Core\CalendarHoliday;
use Carbon\Carbon;

class TeacherPaymentCalculationQuery
{
    private array $daysMap = ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'];

    public function __invoke(int $teacherId, string $periodStart, string $periodEnd): array
    {
        $teacher = Teacher::where('core_person_id', $teacherId)->firstOrFail();

        // Obtener días festivos del período
        $holidays = $this->getHolidays($periodStart, $periodEnd);

        $groups = $this->getGroupsDetail($teacherId, $periodStart, $periodEnd, $holidays);
        $hourlyTotal = collect($groups)->sum('subtotal');
        $advances = $this->getAdvances($teacherId, $periodStart, $periodEnd);

        $absencesCount = 0;
        $attendancesAll = collect();

        if ($teacher->payment_type === 'monthly') {
            $attendancesAll = TeacherAttendance::where('teacher_id', $teacherId)
                ->whereBetween('date', [$periodStart, $periodEnd])
                ->orderBy('date', 'asc')
                ->get();
            $absencesCount = $attendancesAll->where('status', 'absent')->count();
        }

        return [
            'paymentType' => $teacher->payment_type,
            'monthlySalary' => (float) $teacher->monthly_salary,
            'groups' => $groups,
            'hourlyTotal' => round($hourlyTotal, 2),
            'advances' => [
                'total' => (float) $advances->sum('amount'),
                'items' => $advances->map(fn($a) => [
                    'id' => $a->id,
                    'amount' => (float) $a->amount,
                    'date' => $a->advance_date->format('Y-m-d'),
                    'reason' => $a->reason,
                ])->values()->toArray(),
            ],
            'absences' => [
                'count' => $absencesCount,
            ],
            'attendances' => [
                'items' => $attendancesAll->map(fn($a) => [
                    'id' => $a->id,
                    'date' => $a->date->format('Y-m-d'),
                    'status' => $a->status,
                    'checkIn' => $a->check_in,
                    'checkOut' => $a->check_out,
                    'observation' => $a->observation,
                    'groupId' => $a->group_id,
                ])->values()->toArray(),
            ],
        ];
    }

    private function getGroupsDetail(int $teacherId, string $periodStart, string $periodEnd, array $holidays): array
    {
        $groupTeachers = GroupTeacher::with(['group.schedule'])
            ->where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->get();

        return $groupTeachers->map(function ($gt) use ($periodStart, $periodEnd, $holidays) {
            $group = $gt->group;
            $schedule = $group->schedule;

            $startTime = Carbon::parse($schedule->start_time);
            $endTime = Carbon::parse($schedule->end_time);
            $hoursPerDay = round(abs($endTime->diffInMinutes($startTime)) / 60, 2);

            $days = collect(explode(',', $group->days_of_week))
                ->map(fn($d) => $this->daysMap[(int) trim($d)] ?? $d)
                ->implode(', ');

            $scheduleLabel = $startTime->format('H:i') . ' - ' . $endTime->format('H:i');

            $attendances = TeacherAttendance::where('teacher_id', $gt->teacher_id)
                ->where('group_id', $group->id)
                ->whereBetween('date', [$periodStart, $periodEnd])
                ->get();

            $totalDays = $attendances->count();
            $attendedAttendances = $attendances->whereIn('status', ['present', 'late', 'late_justified']);
            
            // Separar días regulares y festivos
            $regularDays = 0;
            $holidayDays = 0;
            
            foreach ($attendedAttendances as $attendance) {
                $dateStr = $attendance->date->format('Y-m-d');
                if (in_array($dateStr, $holidays)) {
                    $holidayDays++;
                } else {
                    $regularDays++;
                }
            }

            $attendedDays = $attendedAttendances->count();
            
            // Calcular subtotales
            $regularRate = (float) $gt->hourly_rate;
            $holidayRate = (float) $gt->holiday_hourly_rate;
            
            $regularSubtotal = $regularDays * $hoursPerDay * $regularRate;
            $holidaySubtotal = $holidayDays * $hoursPerDay * $holidayRate;
            $subtotal = $regularSubtotal + $holidaySubtotal;

            return [
                'groupTeacherId' => $gt->id,
                'groupId' => $group->id,
                'groupName' => $group->name,
                'days' => $days,
                'schedule' => $scheduleLabel,
                'hoursPerDay' => $hoursPerDay,
                'hourlyRate' => $regularRate,
                'holidayHourlyRate' => $holidayRate,
                'attendedDays' => $attendedDays,
                'regularDays' => $regularDays,
                'holidayDays' => $holidayDays,
                'totalDays' => $totalDays,
                'regularSubtotal' => round($regularSubtotal, 2),
                'holidaySubtotal' => round($holidaySubtotal, 2),
                'subtotal' => round($subtotal, 2),
            ];
        })->values()->toArray();
    }

    private function getAdvances(int $teacherId, string $periodStart, string $periodEnd)
    {
        return EmployeeAdvance::where('employee_type', 'profile_teachers')
            ->where('employee_id', $teacherId)
            ->where('status', 'pending')
            ->whereBetween('advance_date', [$periodStart, $periodEnd])
            ->get();
    }

    private function getHolidays(string $periodStart, string $periodEnd): array
    {
        return CalendarHoliday::where('is_active', true)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->pluck('date')
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();
    }
}
