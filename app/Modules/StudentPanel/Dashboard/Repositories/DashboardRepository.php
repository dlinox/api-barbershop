<?php

namespace App\Modules\StudentPanel\Dashboard\Repositories;

use App\Common\Enums\DayOfWeek;
use App\Models\Academy\Attendance;
use App\Models\Academy\Enrollment;
use App\Models\Academy\EnrollmentPaymentDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function summary(int $studentId): array
    {
        $activeEnrollments = Enrollment::where('profile_student_id', $studentId)
            ->where('status', 'active')
            ->count();

        $attendanceRate = $this->computeAttendanceRate($studentId);

        $pendingPayments = $this->pendingPaymentsSummary($studentId);

        $nextClass = $this->nextClassInfo($studentId);

        return [
            'active_enrollments'      => $activeEnrollments,
            'attendance_rate'         => $attendanceRate,
            'pending_payments'        => $pendingPayments['count'],
            'pending_payments_amount' => $pendingPayments['amount'],
            'next_class'              => $nextClass,
        ];
    }

    public function upcomingClasses(int $studentId): Collection
    {
        $enrollments = Enrollment::with([
            'group.schedule',
            'group.room',
            'group.groupTeachers.teacher.person:id,name,paternal_surname',
        ])
            ->where('profile_student_id', $studentId)
            ->where('status', 'active')
            ->get();

        $today = now();
        $classes = collect();

        foreach ($enrollments as $enrollment) {
            $group = $enrollment->group;
            if (!$group || !$group->schedule || !$group->days_of_week) continue;

            $days = collect(explode(',', $group->days_of_week))
                ->map(fn($d) => trim($d))
                ->filter(fn($d) => $d !== '');

            $teacher = $group->groupTeachers->first()?->teacher?->person;
            $teacherName = $teacher ? trim($teacher->name . ' ' . $teacher->paternal_surname) : null;

            foreach ($days as $dayValue) {
                $dayEnum = DayOfWeek::tryFrom($dayValue);
                if (!$dayEnum) continue;

                $dayNumber = (int) $dayValue;
                $classDate = $today->copy()->next($dayNumber);

                if ($classDate->diffInDays($today) > 7) continue;

                $classes->push([
                    'date'    => $classDate->toDateString(),
                    'day'     => $dayEnum->label() . ' ' . $classDate->format('d M'),
                    'group'   => $group->name,
                    'time'    => substr($group->schedule->start_time, 0, 5) . ' - ' . substr($group->schedule->end_time, 0, 5),
                    'room'    => $group->room?->name,
                    'teacher' => $teacherName,
                ]);
            }
        }

        return $classes->sortBy('date')->values()->take(7);
    }

    public function recentAttendance(int $studentId, int $limit = 5): Collection
    {
        return Attendance::select(
            'academy_attendances.id',
            'academy_attendance_deadlines.date',
            'academy_attendances.check_in',
            'academy_attendances.status'
        )
            ->join('academy_attendance_deadlines', 'academy_attendances.attendance_deadline_id', '=', 'academy_attendance_deadlines.id')
            ->join('academy_enrollments', 'academy_attendances.enrollment_id', '=', 'academy_enrollments.id')
            ->where('academy_enrollments.profile_student_id', $studentId)
            ->orderByDesc('academy_attendance_deadlines.date')
            ->limit($limit)
            ->get()
            ->map(fn($a) => [
                'id'       => $a->id,
                'date'     => $a->date,
                'check_in' => $a->check_in ? substr($a->check_in, 0, 5) : null,
                'status'   => $a->status,
            ]);
    }

    // ─── Private Helpers ─────────────────────────────────────

    private function computeAttendanceRate(int $studentId): float
    {
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $stats = Attendance::join('academy_enrollments', 'academy_attendances.enrollment_id', '=', 'academy_enrollments.id')
            ->join('academy_attendance_deadlines', 'academy_attendances.attendance_deadline_id', '=', 'academy_attendance_deadlines.id')
            ->where('academy_enrollments.profile_student_id', $studentId)
            ->whereBetween('academy_attendance_deadlines.date', [$startOfMonth, $endOfMonth])
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN academy_attendances.status IN ('present', 'late', 'late_justified') THEN 1 ELSE 0 END) as attended")
            )
            ->first();

        if (!$stats || $stats->total == 0) return 0;

        return round(($stats->attended / $stats->total) * 100, 1);
    }

    private function pendingPaymentsSummary(int $studentId): array
    {
        $activeEnrollmentIds = Enrollment::where('profile_student_id', $studentId)
            ->where('status', 'active')
            ->pluck('id');

        if ($activeEnrollmentIds->isEmpty()) {
            return ['count' => 0, 'amount' => 0];
        }

        $paidPlanIds = EnrollmentPaymentDetail::join('academy_enrollment_payments', 'academy_enrollment_payments.id', '=', 'academy_enrollment_payment_details.enrollment_payment_id')
            ->where('academy_enrollment_payments.status', 'active')
            ->whereIn('academy_enrollment_payments.enrollment_id', $activeEnrollmentIds)
            ->pluck('academy_enrollment_payment_details.group_payment_plan_id');

        $today = now()->toDateString();

        $pending = DB::table('academy_group_payment_plans')
            ->join('academy_groups', 'academy_groups.id', '=', 'academy_group_payment_plans.group_id')
            ->join('academy_enrollments', 'academy_enrollments.group_id', '=', 'academy_groups.id')
            ->where('academy_enrollments.profile_student_id', $studentId)
            ->where('academy_enrollments.status', 'active')
            ->where('academy_group_payment_plans.end_date', '<=', $today)
            ->whereNotIn('academy_group_payment_plans.id', $paidPlanIds)
            ->select(
                DB::raw('COUNT(DISTINCT academy_group_payment_plans.id) as count'),
                DB::raw('COALESCE(SUM(academy_group_payment_plans.amount), 0) as amount')
            )
            ->first();

        return [
            'count'  => (int) ($pending->count ?? 0),
            'amount' => (float) ($pending->amount ?? 0),
        ];
    }

    private function nextClassInfo(int $studentId): ?array
    {
        $enrollment = Enrollment::with(['group.schedule', 'group.room'])
            ->where('profile_student_id', $studentId)
            ->where('status', 'active')
            ->first();

        if (!$enrollment || !$enrollment->group || !$enrollment->group->schedule) return null;

        $group = $enrollment->group;
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
