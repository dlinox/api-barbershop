<?php

namespace App\Modules\AcademyPanel\Dashboard\Repositories;

use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use App\Models\Academy\Attendance;
use App\Models\Academy\TeacherAttendance;
use App\Models\Treasury\Income;
use App\Models\Treasury\Expense;
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
            ->where('status', 'active')
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

    // ─── Finance Dashboard ────────────────────────────────────────────────────

    public function financeSummary(int $branchId, int $infrastructureId, string $from, string $to): array
    {
        $groupIds             = Group::where('branch_id', $branchId)->pluck('id');
        $enrollmentPaymentIds = $this->enrollmentPaymentIds($groupIds);

        $revenueByType = DB::table('treasury_income_payment_methods as tipm')
            ->join('treasury_incomes as ti', 'tipm.income_id', '=', 'ti.id')
            ->join('core_payment_methods as cpm', 'tipm.payment_method_id', '=', 'cpm.id')
            ->where('ti.status', 'completed')
            ->where('ti.transactionable_type', 'academy_enrollment_payments')
            ->whereIn('ti.transactionable_id', $enrollmentPaymentIds)
            ->whereBetween('ti.transaction_date', [$from, $to])
            ->select('cpm.type', DB::raw('SUM(tipm.amount) as amount'))
            ->groupBy('cpm.type')
            ->get()
            ->pluck('amount', 'type');

        $cashRevenue = (float) ($revenueByType['cash'] ?? 0);
        $bankRevenue = (float) ($revenueByType['bank'] ?? 0);
        $revenue     = $cashRevenue + $bankRevenue;

        $expenses = (float) Expense::where('infrastructure_id', $infrastructureId)
            ->whereIn('status', ['pending', 'approved'])
            ->whereBetween('transaction_date', [$from, $to])
            ->sum('amount');

        $teacherPayments = (float) DB::table('treasury_employee_payments as tep')
            ->join('profile_teachers as pt', 'pt.core_person_id', '=', 'tep.employee_id')
            ->where('tep.employee_type', 'profile_teachers')
            ->where('pt.branch_id', $branchId)
            ->where('tep.status', '!=', 'cancelled')
            ->whereBetween('tep.payment_date', [$from, $to])
            ->sum('tep.total_amount');

        $workerPayments = (float) DB::table('treasury_employee_payments as tep')
            ->join('profile_workers as pw', 'pw.id', '=', 'tep.employee_id')
            ->where('tep.employee_type', 'profile_workers')
            ->where('pw.infrastructure_id', $infrastructureId)
            ->where('tep.status', '!=', 'cancelled')
            ->whereBetween('tep.payment_date', [$from, $to])
            ->sum('tep.total_amount');

        $pendingTeacherAdvances = (float) DB::table('treasury_employee_advances as tea')
            ->join('profile_teachers as pt', 'pt.core_person_id', '=', 'tea.employee_id')
            ->where('tea.employee_type', 'profile_teachers')
            ->where('pt.branch_id', $branchId)
            ->where('tea.status', 'pending')
            ->sum('tea.amount');

        $pendingWorkerAdvances = (float) DB::table('treasury_employee_advances as tea')
            ->join('profile_workers as pw', 'pw.id', '=', 'tea.employee_id')
            ->where('tea.employee_type', 'profile_workers')
            ->where('pw.infrastructure_id', $infrastructureId)
            ->where('tea.status', 'pending')
            ->sum('tea.amount');

        $totalPayments   = $teacherPayments + $workerPayments;
        $totalEgress     = $expenses + $totalPayments;
        $netFlow         = $revenue - $totalEgress;
        $pendingAdvances = $pendingTeacherAdvances + $pendingWorkerAdvances;

        return [
            'revenue'          => $revenue,
            'cashRevenue'      => $cashRevenue,
            'bankRevenue'      => $bankRevenue,
            'expenses'         => $expenses,
            'teacherPayments'  => $teacherPayments,
            'workerPayments'   => $workerPayments,
            'totalPayments'    => $totalPayments,
            'totalEgress'      => $totalEgress,
            'netFlow'          => $netFlow,
            'pendingAdvances'  => $pendingAdvances,
        ];
    }

    public function cashFlow(int $branchId, int $infrastructureId, string $from, string $to): array
    {
        $groupIds             = Group::where('branch_id', $branchId)->pluck('id');
        $enrollmentPaymentIds = $this->enrollmentPaymentIds($groupIds);

        $revenueByDay = Income::where('status', 'completed')
            ->where('transactionable_type', 'academy_enrollment_payments')
            ->whereIn('transactionable_id', $enrollmentPaymentIds)
            ->whereBetween('transaction_date', [$from, $to])
            ->select(
                DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d') as day"),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d')"))
            ->pluck('revenue', 'day');

        $expensesByDay = Expense::where('infrastructure_id', $infrastructureId)
            ->whereIn('status', ['pending', 'approved'])
            ->whereBetween('transaction_date', [$from, $to])
            ->select(
                DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d') as day"),
                DB::raw('SUM(amount) as expenses')
            )
            ->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d')"))
            ->pluck('expenses', 'day');

        $days    = [];
        $current = \Carbon\Carbon::parse($from);
        $end     = \Carbon\Carbon::parse($to);

        while ($current->lte($end)) {
            $day    = $current->toDateString();
            $days[] = [
                'day'      => $day,
                'revenue'  => (float) ($revenueByDay[$day]  ?? 0),
                'expenses' => (float) ($expensesByDay[$day] ?? 0),
            ];
            $current->addDay();
        }

        return $days;
    }

    public function expensesByType(int $infrastructureId, string $from, string $to): array
    {
        return Expense::where('treasury_expenses.infrastructure_id', $infrastructureId)
            ->whereIn('treasury_expenses.status', ['pending', 'approved'])
            ->whereBetween('treasury_expenses.transaction_date', [$from, $to])
            ->join('treasury_expense_types as tet', 'treasury_expenses.expense_type_id', '=', 'tet.id')
            ->select(
                'tet.name as type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(treasury_expenses.amount) as amount')
            )
            ->groupBy('treasury_expenses.expense_type_id', 'tet.name')
            ->orderByDesc('amount')
            ->get()
            ->map(fn($r) => [
                'type'   => $r->type,
                'count'  => (int)   $r->count,
                'amount' => (float) $r->amount,
            ])
            ->toArray();
    }

    public function employeePaymentsSummary(int $branchId, int $infrastructureId, string $from, string $to): array
    {
        $teachers = DB::table('treasury_employee_payments as tep')
            ->join('profile_teachers as pt', 'pt.core_person_id', '=', 'tep.employee_id')
            ->join('core_persons as cp', 'cp.id', '=', 'pt.core_person_id')
            ->where('tep.employee_type', 'profile_teachers')
            ->where('pt.branch_id', $branchId)
            ->where('tep.status', '!=', 'cancelled')
            ->whereBetween('tep.payment_date', [$from, $to])
            ->select(
                DB::raw("CONCAT(cp.name, ' ', cp.paternal_surname) as employee"),
                DB::raw('COUNT(*) as payments'),
                DB::raw('SUM(tep.base_amount) as base_amount'),
                DB::raw('SUM(tep.deductions) as deductions'),
                DB::raw('SUM(tep.total_amount) as total_amount')
            )
            ->groupBy('tep.employee_id', 'cp.name', 'cp.paternal_surname')
            ->get()
            ->map(fn($r) => [
                'employeeName'     => $r->employee,
                'paymentsCount'    => (int)   $r->payments,
                'baseAmount'       => (float) $r->base_amount,
                'deductionsAmount' => (float) $r->deductions,
                'totalPaid'        => (float) $r->total_amount,
            ])
            ->toArray();

        $workers = DB::table('treasury_employee_payments as tep')
            ->join('profile_workers as pw', 'pw.id', '=', 'tep.employee_id')
            ->join('core_persons as cp', 'cp.id', '=', 'pw.id')
            ->where('tep.employee_type', 'profile_workers')
            ->where('pw.infrastructure_id', $infrastructureId)
            ->where('tep.status', '!=', 'cancelled')
            ->whereBetween('tep.payment_date', [$from, $to])
            ->select(
                DB::raw("CONCAT(cp.name, ' ', cp.paternal_surname) as employee"),
                DB::raw('COUNT(*) as payments'),
                DB::raw('SUM(tep.base_amount) as base_amount'),
                DB::raw('SUM(tep.deductions) as deductions'),
                DB::raw('SUM(tep.total_amount) as total_amount')
            )
            ->groupBy('tep.employee_id', 'cp.name', 'cp.paternal_surname')
            ->get()
            ->map(fn($r) => [
                'employeeName'     => $r->employee,
                'paymentsCount'    => (int)   $r->payments,
                'baseAmount'       => (float) $r->base_amount,
                'deductionsAmount' => (float) $r->deductions,
                'totalPaid'        => (float) $r->total_amount,
            ])
            ->toArray();

        return ['teachers' => $teachers, 'workers' => $workers];
    }

    public function pendingAdvances(int $branchId, int $infrastructureId): array
    {
        $teachers = DB::table('treasury_employee_advances as tea')
            ->join('profile_teachers as pt', 'pt.core_person_id', '=', 'tea.employee_id')
            ->join('core_persons as cp', 'cp.id', '=', 'pt.core_person_id')
            ->where('tea.employee_type', 'profile_teachers')
            ->where('pt.branch_id', $branchId)
            ->where('tea.status', 'pending')
            ->select(
                DB::raw("CONCAT(cp.name, ' ', cp.paternal_surname) as employee"),
                DB::raw("'Docente' as role"),
                'tea.amount',
                'tea.advance_date',
                'tea.reason'
            )
            ->get();

        $workers = DB::table('treasury_employee_advances as tea')
            ->join('profile_workers as pw', 'pw.id', '=', 'tea.employee_id')
            ->join('core_persons as cp', 'cp.id', '=', 'pw.id')
            ->where('tea.employee_type', 'profile_workers')
            ->where('pw.infrastructure_id', $infrastructureId)
            ->where('tea.status', 'pending')
            ->select(
                DB::raw("CONCAT(cp.name, ' ', cp.paternal_surname) as employee"),
                DB::raw("'Trabajador' as role"),
                'tea.amount',
                'tea.advance_date',
                'tea.reason'
            )
            ->get();

        return $teachers->concat($workers)
            ->sortByDesc('advance_date')
            ->values()
            ->map(fn($r) => [
                'employeeName' => $r->employee,
                'employeeRole' => $r->role,
                'amount'       => (float) $r->amount,
                'date'         => $r->advance_date,
                'reason'       => $r->reason,
            ])
            ->toArray();
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    private function enrollmentPaymentIds($groupIds)
    {
        return DB::table('academy_enrollment_payments as aep')
            ->join('academy_enrollments as ae', 'aep.enrollment_id', '=', 'ae.id')
            ->whereIn('ae.group_id', $groupIds)
            ->pluck('aep.id');
    }

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