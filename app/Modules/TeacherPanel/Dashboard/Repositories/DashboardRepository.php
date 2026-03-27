<?php

namespace App\Modules\TeacherPanel\Dashboard\Repositories;

use App\Common\Enums\DayOfWeek;
use App\Models\Academy\GroupTeacher;
use App\Models\Academy\TeacherAttendance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function summary(int $teacherId): array
    {
        $activeGroups = GroupTeacher::where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->count();

        $totalStudents = $this->countTotalStudents($teacherId);

        $attendanceRate = $this->computeAttendanceRate($teacherId);

        $nextClass = $this->nextClassInfo($teacherId);

        return [
            'active_groups'  => $activeGroups,
            'total_students' => $totalStudents,
            'attendance_rate' => $attendanceRate,
            'next_class'     => $nextClass,
        ];
    }

    public function upcomingClasses(int $teacherId): Collection
    {
        $groupTeachers = GroupTeacher::with([
            'group.schedule',
            'group.room',
            'group.enrollments' => fn($q) => $q->where('status', 'active'),
        ])
            ->where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->get();

        $today = now();
        $classes = collect();

        foreach ($groupTeachers as $gt) {
            $group = $gt->group;
            if (!$group || !$group->schedule || !$group->days_of_week) continue;

            $days = collect(explode(',', $group->days_of_week))
                ->map(fn($d) => trim($d))
                ->filter(fn($d) => $d !== '');

            $studentsCount = $group->enrollments->count();

            foreach ($days as $dayValue) {
                $dayEnum = DayOfWeek::tryFrom($dayValue);
                if (!$dayEnum) continue;

                $dayNumber = (int) $dayValue;
                $classDate = $today->copy()->next($dayNumber);

                if ($classDate->diffInDays($today) > 7) continue;

                $classes->push([
                    'date'     => $classDate->toDateString(),
                    'day'      => $dayEnum->label() . ' ' . $classDate->format('d M'),
                    'group'    => $group->name,
                    'time'     => substr($group->schedule->start_time, 0, 5) . ' - ' . substr($group->schedule->end_time, 0, 5),
                    'room'     => $group->room?->name,
                    'students' => $studentsCount,
                ]);
            }
        }

        return $classes->sortBy('date')->values()->take(7);
    }

    public function recentAttendance(int $teacherId, int $limit = 5): Collection
    {
        return TeacherAttendance::select(
            'academy_teacher_attendances.id',
            'academy_teacher_attendances.date',
            'academy_teacher_attendances.check_in',
            'academy_teacher_attendances.status',
            'academy_groups.name as group_name'
        )
            ->join('academy_groups', 'academy_teacher_attendances.group_id', '=', 'academy_groups.id')
            ->where('academy_teacher_attendances.teacher_id', $teacherId)
            ->orderByDesc('academy_teacher_attendances.date')
            ->limit($limit)
            ->get()
            ->map(fn($a) => [
                'id'       => $a->id,
                'date'     => $a->date->format('Y-m-d'),
                'group'    => $a->group_name,
                'check_in' => $a->check_in ? substr($a->check_in, 0, 5) : null,
                'status'   => $a->status,
            ]);
    }

    // ─── Private Helpers ─────────────────────────────────────

    private function countTotalStudents(int $teacherId): int
    {
        return DB::table('academy_group_teachers')
            ->join('academy_enrollments', 'academy_enrollments.group_id', '=', 'academy_group_teachers.group_id')
            ->where('academy_group_teachers.teacher_id', $teacherId)
            ->where('academy_group_teachers.status', 'active')
            ->where('academy_enrollments.status', 'active')
            ->count(DB::raw('DISTINCT academy_enrollments.profile_student_id'));
    }

    private function computeAttendanceRate(int $teacherId): float
    {
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $stats = TeacherAttendance::where('teacher_id', $teacherId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status IN ('present', 'late', 'late_justified') THEN 1 ELSE 0 END) as attended")
            )
            ->first();

        if (!$stats || $stats->total == 0) return 0;

        return round(($stats->attended / $stats->total) * 100, 1);
    }

    private function nextClassInfo(int $teacherId): ?array
    {
        $groupTeacher = GroupTeacher::with(['group.schedule', 'group.room'])
            ->where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->first();

        if (!$groupTeacher || !$groupTeacher->group || !$groupTeacher->group->schedule) return null;

        $group = $groupTeacher->group;
        if (!$group->days_of_week) return null;

        $daysLabels = collect(explode(',', $group->days_of_week))
            ->map(fn($d) => DayOfWeek::tryFrom(trim($d))?->label())
            ->filter()
            ->implode(', ');

        if (!$daysLabels) return null;

        $time = $daysLabels . ' — ' . substr($group->schedule->start_time, 0, 5) . ' a ' . substr($group->schedule->end_time, 0, 5);

        return [
            'group' => $group->name,
            'time'  => $time,
            'room'  => $group->room?->name,
        ];
    }
}
