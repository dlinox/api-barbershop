<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Academy\AttendanceDeadline;
use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use App\Models\Core\Company;
use Carbon\Carbon;

class AcademyAttendanceByGroupQuery
{
    public function __invoke(int $groupId, int $month, int $year): array
    {
        $group = Group::with(['branch', 'level', 'schedule', 'room'])->findOrFail($groupId);

        $enrollments = Enrollment::where('group_id', $groupId)
            ->where('status', 'active')
            ->with('student.person')
            ->get()
            ->sortBy(fn ($e) => $e->student?->person?->paternal_surname . ' ' . $e->student?->person?->name);

        $startOfMonth = Carbon::create($year, $month, 1);
        $daysInMonth = $startOfMonth->daysInMonth;

        $deadlines = AttendanceDeadline::where('group_id', $groupId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('attendances')
            ->get()
            ->keyBy(fn ($d) => Carbon::parse($d->date)->day);

        $students = [];
        foreach ($enrollments as $enrollment) {
            $person = $enrollment->student?->person;
            $studentName = $person
                ? trim($person->paternal_surname . ' ' . $person->maternal_surname . ', ' . $person->name)
                : '---';

            $days = [];
            $presentCount = 0;
            $totalClassDays = 0;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $deadline = $deadlines->get($day);
                if (!$deadline) {
                    $days[$day] = null;
                    continue;
                }
                $totalClassDays++;
                $attendance = $deadline->attendances->firstWhere('enrollment_id', $enrollment->id);
                $status = $attendance?->status ?? null;
                $days[$day] = $status;

                if (in_array($status, ['present', 'late', 'late_justified'])) {
                    $presentCount++;
                }
            }

            $attendanceRate = $totalClassDays > 0
                ? round(($presentCount / $totalClassDays) * 100, 1)
                : 0;

            $students[] = [
                'name'             => $studentName,
                'days'             => $days,
                'attendance_rate'  => $attendanceRate,
                'present_count'    => $presentCount,
                'total_class_days' => $totalClassDays,
            ];
        }

        $classDays = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $classDays[$day] = $deadlines->has($day);
        }

        return [
            'group'         => $group,
            'month'         => $month,
            'year'          => $year,
            'days_in_month' => $daysInMonth,
            'class_days'    => $classDays,
            'students'      => $students,
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $group = $queryData['group'];
        $monthNames = [
            1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
            5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
            9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE',
        ];

        return [
            'company'         => Company::first(),
            'branch'          => $group->branch ?? null,
            'report_title'    => 'REGISTRO DE ASISTENCIA POR GRUPO',
            'report_subtitle' => 'ESCUELA',
            'report_date'     => $monthNames[$queryData['month']] . ' ' . $queryData['year'],
            'report_day'      => $group->name,
            'group_name'      => $group->name,
            'branch_name'     => $group->branch->name,
            'level_name'      => $group->level->name,
            'schedule_time'   => $group->schedule
                ? $group->schedule->start_time . ' - ' . $group->schedule->end_time
                : '---',
            'room_name'       => $group->room->name ?? '---',
            'month_name'      => $monthNames[$queryData['month']],
            'year'            => $queryData['year'],
            'days_in_month'   => $queryData['days_in_month'],
            'class_days'      => $queryData['class_days'],
            'students'        => $queryData['students'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        $group = $queryData['group'];
        $totalStudents = count($queryData['students']);
        $avgRate = $totalStudents > 0
            ? round(collect($queryData['students'])->avg('attendance_rate'), 1)
            : 0;

        return [
            'group_id'        => $group->id,
            'group_name'      => $group->name,
            'branch_name'     => $group->branch->name,
            'month'           => $queryData['month'],
            'year'            => $queryData['year'],
            'student_count'   => $totalStudents,
            'attendance_rate' => $avgRate,
        ];
    }
}
