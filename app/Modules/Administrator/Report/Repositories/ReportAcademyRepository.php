<?php

namespace App\Modules\Administrator\Report\Repositories;

use App\Models\Academy\Attendance;
use App\Models\Academy\Branch;
use App\Models\Academy\Enrollment;
use App\Models\Academy\EnrollmentPaymentDetail;
use App\Models\Academy\Group;
use App\Models\Behavior\Profile;
use App\Models\Treasury\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportAcademyRepository
{
    public function summary(string $from, string $to, ?int $branchId = null): array
    {
        $enrollmentsActive = Enrollment::where('status', 'active')
            ->when($branchId, fn($q) => $q->whereHas('group', fn($g) => $g->where('branch_id', $branchId)))
            ->count();

        $enrollmentsPeriod = Enrollment::whereBetween('date', [$from, $to])
            ->when($branchId, fn($q) => $q->whereHas('group', fn($g) => $g->where('branch_id', $branchId)))
            ->count();

        $attendance = $this->attendanceStats($from, $to, $branchId);
        $totalAttendance = $attendance['present'] + $attendance['absent'] + $attendance['late'] + $attendance['justified'];
        $attendanceRate = $totalAttendance > 0
            ? round(($attendance['present'] / $totalAttendance) * 100, 1)
            : 0;

        $income = Income::where('status', 'completed')
            ->where('transactionable_type', 'academy_enrollment_payments')
            ->whereBetween('transaction_date', [$from, $to]);

        if ($branchId) {
            $income->whereHas('infrastructure', function ($q) use ($branchId) {
                $q->whereIn('id', function ($sub) use ($branchId) {
                    $sub->select('infrastructure_id')
                        ->from('academy_branches')
                        ->where('id', $branchId);
                });
            });
        }

        $pendingPayments = $this->pendingPayments($from, $to, $branchId);

        return [
            'enrollments_active' => $enrollmentsActive,
            'enrollments_period' => $enrollmentsPeriod,
            'attendance_rate'    => $attendanceRate,
            'attendance'         => $attendance,
            'total_income'       => (float) $income->sum('total'),
            'income_count'       => $income->count(),
            'pending_payments'   => $pendingPayments['count'],
            'pending_amount'     => $pendingPayments['amount'],
        ];
    }

    public function enrollmentTrend(string $from, string $to, ?int $branchId = null): array
    {
        $enrollments = Enrollment::select(
            DB::raw("DATE_FORMAT(date, '%Y-%m') as period"),
            DB::raw('COUNT(*) as total'),
        )
            ->when($branchId, fn($q) => $q->whereHas('group', fn($g) => $g->where('branch_id', $branchId)))
            ->whereBetween('date', [$from, $to])
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period');

        $allPeriods = collect();
        $start = Carbon::parse($from)->startOfMonth();
        $end = Carbon::parse($to);

        while ($start->lte($end)) {
            $allPeriods->push($start->format('Y-m'));
            $start->addMonth();
        }

        return [
            'categories' => $allPeriods->toArray(),
            'series'     => [
                ['name' => 'Matrículas', 'data' => $allPeriods->map(fn($p) => (int) ($enrollments[$p] ?? 0))->toArray()],
            ],
        ];
    }

    public function revenueByType(string $from, string $to, ?int $branchId = null): array
    {
        $query = EnrollmentPaymentDetail::select(
            'academy_enrollment_payment_details.type',
            DB::raw("DATE_FORMAT(treasury_incomes.transaction_date, '%Y-%m') as period"),
            DB::raw('SUM(academy_enrollment_payment_details.total) as total'),
        )
            ->join('academy_enrollment_payments', 'academy_enrollment_payments.id', '=', 'academy_enrollment_payment_details.enrollment_payment_id')
            ->join('treasury_incomes', function ($join) {
                $join->on('treasury_incomes.transactionable_id', '=', 'academy_enrollment_payments.id')
                    ->where('treasury_incomes.transactionable_type', '=', 'academy_enrollment_payments');
            })
            ->where('treasury_incomes.status', 'completed')
            ->where('academy_enrollment_payments.status', 'active')
            ->whereBetween('treasury_incomes.transaction_date', [$from, $to]);

        if ($branchId) {
            $query->join('academy_enrollments', 'academy_enrollments.id', '=', 'academy_enrollment_payments.enrollment_id')
                ->join('academy_groups', 'academy_groups.id', '=', 'academy_enrollments.group_id')
                ->where('academy_groups.branch_id', $branchId);
        }

        $details = $query->groupBy('academy_enrollment_payment_details.type', 'period')
            ->orderBy('period')
            ->get();

        $periods = $details->pluck('period')->unique()->sort()->values();
        $enrollment = [];
        $monthly = [];

        foreach ($periods as $period) {
            $enrollment[] = (float) ($details->where('type', 'enrollment')->where('period', $period)->first()?->total ?? 0);
            $monthly[] = (float) ($details->where('type', 'monthly')->where('period', $period)->first()?->total ?? 0);
        }

        return [
            'categories' => $periods->toArray(),
            'series'     => [
                ['name' => 'Matrícula', 'data' => $enrollment],
                ['name' => 'Mensualidad', 'data' => $monthly],
            ],
        ];
    }

    public function groupOccupancy(?int $branchId = null): array
    {
        $groups = Group::select(
            'academy_groups.id',
            'academy_groups.name',
            'academy_rooms.capacity',
        )
            ->join('academy_rooms', 'academy_rooms.id', '=', 'academy_groups.room_id')
            ->where('academy_groups.is_active', true)
            ->when($branchId, fn($q) => $q->where('academy_groups.branch_id', $branchId))
            ->withCount(['enrollments' => function ($q) {
                $q->where('status', 'active');
            }])
            ->get();

        return $groups->map(fn($g) => [
            'group'    => $g->name,
            'enrolled' => $g->enrollments_count,
            'capacity' => $g->capacity,
        ])->toArray();
    }

    public function groupDetail(string $from, string $to, ?int $branchId = null): array
    {
        $groups = Group::select(
            'academy_groups.id',
            'academy_groups.name',
            'academy_groups.is_active',
            'academy_levels.name as level_name',
        )
            ->join('academy_levels', 'academy_levels.id', '=', 'academy_groups.level_id')
            ->when($branchId, fn($q) => $q->where('academy_groups.branch_id', $branchId))
            ->orderBy('academy_levels.order')
            ->orderBy('academy_groups.name')
            ->get();

        return $groups->map(function ($group) use ($from, $to) {
            $activeEnrollments = Enrollment::where('group_id', $group->id)
                ->where('status', 'active')
                ->count();

            $attendanceQuery = Attendance::join('academy_attendance_deadlines', 'academy_attendance_deadlines.id', '=', 'academy_attendances.attendance_deadline_id')
                ->where('academy_attendance_deadlines.group_id', $group->id)
                ->whereBetween('academy_attendance_deadlines.date', [$from, $to]);

            $total = (clone $attendanceQuery)->count();
            $present = (clone $attendanceQuery)->where('academy_attendances.status', 'present')->count();
            $attendanceRate = $total > 0 ? round(($present / $total) * 100, 1) : 0;

            $enrollmentIds = Enrollment::where('group_id', $group->id)->pluck('id');
            $revenue = 0;

            if ($enrollmentIds->isNotEmpty()) {
                $revenue = (float) Income::where('status', 'completed')
                    ->where('transactionable_type', 'academy_enrollment_payments')
                    ->whereBetween('transaction_date', [$from, $to])
                    ->whereIn('transactionable_id', function ($q) use ($enrollmentIds) {
                        $q->select('id')
                            ->from('academy_enrollment_payments')
                            ->where('status', 'active')
                            ->whereIn('enrollment_id', $enrollmentIds);
                    })
                    ->sum('total');
            }

            return [
                'group'          => $group->name,
                'level'          => $group->level_name,
                'students'       => $activeEnrollments,
                'attendanceRate' => $attendanceRate,
                'revenue'        => $revenue,
                'status'         => $group->is_active ? 'active' : 'closed',
            ];
        })->toArray();
    }

    public function dailyIncome(string $date, ?int $branchId = null): array
    {
        $branch = $branchId ? Branch::find($branchId) : null;

        $query = Income::where('treasury_incomes.status', 'completed')
            ->where('treasury_incomes.transactionable_type', 'academy_enrollment_payments')
            ->whereDate('treasury_incomes.transaction_date', $date)
            ->with([
                'person',
                'paymentMethods.paymentMethod',
                'transactionable.enrollment.student.person',
                'transactionable.enrollment.group',
                'transactionable.details',
            ]);

        if ($branchId) {
            $query->whereHas('infrastructure', function ($q) use ($branchId) {
                $q->whereIn('id', function ($sub) use ($branchId) {
                    $sub->select('infrastructure_id')
                        ->from('academy_branches')
                        ->where('id', $branchId);
                });
            });
        }

        $incomes = $query->orderBy('treasury_incomes.receipt_number')->get();

        // Responsible person: first income's user -> profile -> worker -> person
        $responsible = ['name' => '---', 'position' => '---'];
        if ($incomes->isNotEmpty()) {
            $userId = $incomes->first()->user_id;
            $profile = Profile::where('auth_user_id', $userId)->first();
            if ($profile) {
                $person = $profile->person;
                if ($person) {
                    $responsible['name'] = $person->full_name;
                }
                $profileable = $profile->profileable;
                if ($profileable && isset($profileable->position)) {
                    $responsible['position'] = $profileable->position ?? '---';
                }
            }
        }

        $typeLabels = ['enrollment' => 'MATRÍCULA', 'monthly' => 'MENSUALIDAD'];
        $receiptTypeLabels = ['00' => 'Recibo Interno'];

        $rows = [];
        $totalCash = 0;
        $totalBank = 0;

        foreach ($incomes as $income) {
            $enrollment = $income->transactionable?->enrollment;
            $studentPerson = $enrollment?->student?->person;
            $studentName = $studentPerson ? $studentPerson->full_name : '---';
            $groupName = $enrollment?->group?->name ?? '';

            // Build description from payment details (e.g. "MENSUALIDAD - Juan Pérez")
            $paymentDetails = $income->transactionable?->details ?? collect();
            $types = $paymentDetails->pluck('type')->unique()->map(fn($t) => $typeLabels[$t] ?? strtoupper($t))->implode(' / ');
            $description = ($types ? $types . ' - ' : '') . $studentName;

            // Receipt info
            $receiptType = $receiptTypeLabels[$income->receipt_type] ?? $income->receipt_type;
            $serieNumber = $income->receipt_serie . '-' . str_pad((string) $income->receipt_number, 8, '0', STR_PAD_LEFT);

            // Payment method (could be split, take first or combine)
            $paymentMethodNames = [];
            $paymentReference = '';
            foreach ($income->paymentMethods as $pm) {
                $methodName = $pm->paymentMethod?->name ?? '---';
                $methodType = $pm->paymentMethod?->type ?? 'cash';
                $paymentMethodNames[] = $methodName;
                if ($pm->payment_reference) {
                    $paymentReference = $pm->payment_reference;
                }

                if ($methodType === 'cash') {
                    $totalCash += (float) $pm->amount;
                } else {
                    $totalBank += (float) $pm->amount;
                }
            }

            $rows[] = [
                'description'      => $description,
                'group'            => $groupName,
                'receiptType'      => $receiptType,
                'serieNumber'      => $serieNumber,
                'paymentMethod'    => implode(' / ', $paymentMethodNames),
                'paymentReference' => $paymentReference ?: '---',
                'amount'           => (float) $income->total,
            ];
        }

        $parsedDate = Carbon::parse($date);
        $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return [
            'date'        => $date,
            'dayName'     => $dayNames[$parsedDate->dayOfWeek],
            'branch'      => $branch?->name ?? 'Todas las sedes',
            'responsible' => $responsible,
            'rows'        => $rows,
            'totals'      => [
                'cash'  => $totalCash,
                'bank'  => $totalBank,
                'total' => $totalCash + $totalBank,
            ],
        ];
    }

    private function attendanceStats(string $from, string $to, ?int $branchId = null): array
    {
        $query = Attendance::join('academy_attendance_deadlines', 'academy_attendance_deadlines.id', '=', 'academy_attendances.attendance_deadline_id')
            ->whereBetween('academy_attendance_deadlines.date', [$from, $to]);

        if ($branchId) {
            $query->join('academy_groups', 'academy_groups.id', '=', 'academy_attendance_deadlines.group_id')
                ->where('academy_groups.branch_id', $branchId);
        }

        $stats = $query->select(
            DB::raw("SUM(CASE WHEN academy_attendances.status = 'present' THEN 1 ELSE 0 END) as present"),
            DB::raw("SUM(CASE WHEN academy_attendances.status = 'absent' THEN 1 ELSE 0 END) as absent"),
            DB::raw("SUM(CASE WHEN academy_attendances.status = 'late' THEN 1 ELSE 0 END) as late"),
            DB::raw("SUM(CASE WHEN academy_attendances.status IN ('absent_justified', 'late_justified') THEN 1 ELSE 0 END) as justified"),
        )->first();

        return [
            'present'   => (int) ($stats->present ?? 0),
            'absent'    => (int) ($stats->absent ?? 0),
            'late'      => (int) ($stats->late ?? 0),
            'justified' => (int) ($stats->justified ?? 0),
        ];
    }

    private function pendingPayments(string $from, string $to, ?int $branchId = null): array
    {
        $activeEnrollmentIds = Enrollment::where('status', 'active')
            ->when($branchId, fn($q) => $q->whereHas('group', fn($g) => $g->where('branch_id', $branchId)))
            ->pluck('id');

        if ($activeEnrollmentIds->isEmpty()) {
            return ['count' => 0, 'amount' => 0];
        }

        $paidPlanIds = EnrollmentPaymentDetail::join('academy_enrollment_payments', 'academy_enrollment_payments.id', '=', 'academy_enrollment_payment_details.enrollment_payment_id')
            ->where('academy_enrollment_payments.status', 'active')
            ->whereIn('academy_enrollment_payments.enrollment_id', $activeEnrollmentIds)
            ->pluck('academy_enrollment_payment_details.group_payment_plan_id');

        $pending = DB::table('academy_group_payment_plans')
            ->join('academy_groups', 'academy_groups.id', '=', 'academy_group_payment_plans.group_id')
            ->join('academy_enrollments', 'academy_enrollments.group_id', '=', 'academy_groups.id')
            ->where('academy_enrollments.status', 'active')
            ->where('academy_group_payment_plans.end_date', '<=', $to)
            ->whereNotIn('academy_group_payment_plans.id', $paidPlanIds)
            ->when($branchId, fn($q) => $q->where('academy_groups.branch_id', $branchId))
            ->select(
                DB::raw('COUNT(DISTINCT academy_group_payment_plans.id) as count'),
                DB::raw('COALESCE(SUM(academy_group_payment_plans.amount), 0) as amount'),
            )
            ->first();

        return [
            'count'  => (int) ($pending->count ?? 0),
            'amount' => (float) ($pending->amount ?? 0),
        ];
    }
}
