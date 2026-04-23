<?php

namespace App\Modules\AcademyPanel\Dashboard\Repositories;

use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use App\Models\Academy\Attendance;
use App\Models\Academy\TeacherAttendance;
use App\Models\Treasury\Income;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function summary(int $branchId): array
    {
        $today        = now()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth   = now()->endOfMonth()->toDateString();

        $activeGroupIds = Group::where('branch_id', $branchId)
            ->where('is_active', true)
            ->pluck('id');

        $activeGroupsCount = $activeGroupIds->count();

        $enrolledStudentsCount = Enrollment::whereIn('group_id', $activeGroupIds)
            ->where('status', 'active')
            ->distinct('profile_student_id')
            ->count('profile_student_id');

        $attendanceToday = Attendance::join('academy_attendance_deadlines', 'academy_attendances.attendance_deadline_id', '=', 'academy_attendance_deadlines.id')
            ->join('academy_enrollments', 'academy_attendances.enrollment_id', '=', 'academy_enrollments.id')
            ->whereIn('academy_enrollments.group_id', $activeGroupIds)
            ->whereDate('academy_attendance_deadlines.date', $today)
            ->count();

        $enrollmentPaymentIdsMonth = DB::table('academy_enrollment_payments as aep')
            ->join('academy_enrollments as ae', 'aep.enrollment_id', '=', 'ae.id')
            ->whereIn('ae.group_id', $activeGroupIds)
            ->pluck('aep.id');

        $incomeMonthQuery = Income::where('status', 'completed')
            ->where('transactionable_type', 'academy_enrollment_payments')
            ->whereIn('transactionable_id', $enrollmentPaymentIdsMonth)
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth]);

        $incomeMonth     = (float) $incomeMonthQuery->sum('total');
        $incomeMonthCount = (int) $incomeMonthQuery->count();

        // Payment breakdown for this month
        $monthBreakdown = $this->paymentBreakdownBase($branchId, $startOfMonth, $endOfMonth);
        $cashMonth  = (float) $monthBreakdown->where('type', 'cash')->sum('amount');
        $bankMonth  = (float) $monthBreakdown->where('type', 'bank')->sum('amount');

        // Enrollments this month
        $enrollmentsThisMonth = Enrollment::whereIn('group_id', $activeGroupIds)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->count();

        return [
            'active_groups_count'       => $activeGroupsCount,
            'enrolled_students_count'   => $enrolledStudentsCount,
            'attendance_today'          => $attendanceToday,
            'income_month'              => $incomeMonth,
            'income_month_count'        => $incomeMonthCount,
            'cash_month'                => $cashMonth,
            'bank_month'                => $bankMonth,
            'enrollments_this_month'    => $enrollmentsThisMonth,
        ];
    }

    public function enrollmentsByGroup(int $branchId, string $from, string $to): Collection
    {
        return Enrollment::whereBetween('academy_enrollments.date', [$from, $to])
            ->join('academy_groups', 'academy_enrollments.group_id', '=', 'academy_groups.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->where('academy_groups.branch_id', $branchId)
            ->select(
                'academy_groups.name as group_name',
                'academy_levels.name as level',
                DB::raw('COUNT(*) as enrollments'),
                DB::raw("SUM(CASE WHEN academy_enrollments.status = 'active' THEN 1 ELSE 0 END) as active"),
                DB::raw("SUM(CASE WHEN academy_enrollments.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled")
            )
            ->groupBy('academy_enrollments.group_id', 'academy_groups.name', 'academy_levels.name')
            ->orderByDesc('enrollments')
            ->get();
    }

    public function attendanceOverview(int $branchId, string $from, string $to): array
    {
        $groupIds = Group::where('branch_id', $branchId)->pluck('id');

        $studentAttendance = Attendance::join('academy_enrollments', 'academy_attendances.enrollment_id', '=', 'academy_enrollments.id')
            ->join('academy_attendance_deadlines', 'academy_attendances.attendance_deadline_id', '=', 'academy_attendance_deadlines.id')
            ->whereIn('academy_enrollments.group_id', $groupIds)
            ->whereBetween('academy_attendance_deadlines.date', [$from, $to])
            ->select('academy_attendances.status', DB::raw('COUNT(*) as count'))
            ->groupBy('academy_attendances.status')
            ->pluck('count', 'status');

        $teacherAttendance = TeacherAttendance::whereIn('group_id', $groupIds)
            ->whereBetween('date', [$from, $to])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'students' => [
                'present'   => (int) ($studentAttendance['present'] ?? 0),
                'absent'    => (int) ($studentAttendance['absent'] ?? 0),
                'late'      => (int) ($studentAttendance['late'] ?? 0),
                'justified' => (int) (($studentAttendance['absent_justified'] ?? 0) + ($studentAttendance['late_justified'] ?? 0)),
            ],
            'teachers' => [
                'present'   => (int) ($teacherAttendance['present'] ?? 0),
                'absent'    => (int) ($teacherAttendance['absent'] ?? 0),
                'late'      => (int) ($teacherAttendance['late'] ?? 0),
                'justified' => (int) (($teacherAttendance['absent_justified'] ?? 0) + ($teacherAttendance['late_justified'] ?? 0)),
            ],
        ];
    }

    public function incomeByDay(int $branchId, string $from, string $to): Collection
    {
        $groupIds = Group::where('branch_id', $branchId)->pluck('id');

        return Income::where('status', 'completed')
            ->where('transactionable_type', 'academy_enrollment_payments')
            ->whereIn('transactionable_id', function ($q) use ($groupIds) {
                $q->select('aep.id')
                    ->from('academy_enrollment_payments as aep')
                    ->join('academy_enrollments as ae', 'aep.enrollment_id', '=', 'ae.id')
                    ->whereIn('ae.group_id', $groupIds);
            })
            ->whereBetween('transaction_date', [$from, $to])
            ->select(
                DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d') as day"),
                DB::raw('COUNT(*) as payments'),
                DB::raw('SUM(total) as income')
            )
            ->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d')"))
            ->orderBy('day')
            ->get();
    }

    /**
     * Breakdown of income by payment method for the given date range.
     */
    public function paymentBreakdown(int $branchId, string $from, string $to): array
    {
        $methods = $this->paymentBreakdownBase($branchId, $from, $to);

        $total     = $methods->sum('amount');
        $cashTotal = $methods->where('type', 'cash')->sum('amount');
        $bankTotal = $methods->where('type', 'bank')->sum('amount');

        return [
            'methods'   => $methods->map(fn($m) => [
                'name'       => $m->name,
                'type'       => $m->type,
                'amount'     => (float) $m->amount,
                'percentage' => $total > 0 ? round($m->amount / $total * 100, 1) : 0,
            ])->values(),
            'cashTotal' => (float) $cashTotal,
            'bankTotal' => (float) $bankTotal,
            'total'     => (float) $total,
        ];
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    private function paymentBreakdownBase(int $branchId, string $from, string $to): Collection
    {
        $groupIds = Group::where('branch_id', $branchId)->pluck('id');

        return DB::table('treasury_income_payment_methods as tipm')
            ->join('treasury_incomes as ti', 'tipm.income_id', '=', 'ti.id')
            ->join('core_payment_methods as cpm', 'tipm.payment_method_id', '=', 'cpm.id')
            ->where('ti.status', 'completed')
            ->where('ti.transactionable_type', 'academy_enrollment_payments')
            ->whereIn('ti.transactionable_id', function ($q) use ($groupIds) {
                $q->select('aep.id')
                    ->from('academy_enrollment_payments as aep')
                    ->join('academy_enrollments as ae', 'aep.enrollment_id', '=', 'ae.id')
                    ->whereIn('ae.group_id', $groupIds->toArray());
            })
            ->whereBetween('ti.transaction_date', [$from, $to])
            ->select('cpm.name', 'cpm.type', DB::raw('SUM(tipm.amount) as amount'))
            ->groupBy('cpm.id', 'cpm.name', 'cpm.type')
            ->orderByDesc('amount')
            ->get();
    }
}