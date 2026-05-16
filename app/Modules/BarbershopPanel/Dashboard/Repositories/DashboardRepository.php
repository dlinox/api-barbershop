<?php

namespace App\Modules\BarbershopPanel\Dashboard\Repositories;

use App\Models\Barbershop\Ticket;
use App\Models\Profile\Barber;
use App\Models\Barbershop\TicketService;
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

        $ticketsToday = Ticket::where('branch_id', $branchId)
            ->whereDate('ticket_date', $today)
            ->where('status', 'confirmed')
            ->count();

        $ticketsMonth = Ticket::where('branch_id', $branchId)
            ->whereDate('ticket_date', '>=', $startOfMonth)
            ->whereDate('ticket_date', '<=', $endOfMonth)
            ->where('status', 'confirmed')
            ->count();

        $revenueToday = (float) Ticket::where('branch_id', $branchId)
            ->whereDate('ticket_date', $today)
            ->where('status', 'confirmed')
            ->sum('total');

        $revenueMonth = (float) Ticket::where('branch_id', $branchId)
            ->whereDate('ticket_date', '>=', $startOfMonth)
            ->whereDate('ticket_date', '<=', $endOfMonth)
            ->where('status', 'confirmed')
            ->sum('total');

        $activeBarbersCount = Barber::where('branch_id', $branchId)
            ->where('is_active', true)
            ->count();

        $avgTicketMonth = $ticketsMonth > 0
            ? round($revenueMonth / $ticketsMonth, 2)
            : 0.0;

        // Payment breakdown for today
        $todayBreakdown = $this->paymentBreakdownBase($branchId, $today, $today);
        $cashToday  = (float) $todayBreakdown->where('type', 'cash')->sum('amount');
        $bankToday  = (float) $todayBreakdown->where('type', 'bank')->sum('amount');

        // Payment breakdown for month
        $monthBreakdown = $this->paymentBreakdownBase($branchId, $startOfMonth, $endOfMonth);
        $cashMonth  = (float) $monthBreakdown->where('type', 'cash')->sum('amount');
        $bankMonth  = (float) $monthBreakdown->where('type', 'bank')->sum('amount');

        return [
            'tickets_today'         => $ticketsToday,
            'tickets_month'         => $ticketsMonth,
            'revenue_today'         => $revenueToday,
            'revenue_month'         => $revenueMonth,
            'active_barbers_count'  => $activeBarbersCount,
            'avg_ticket_month'      => $avgTicketMonth,
            'cash_today'            => $cashToday,
            'bank_today'            => $bankToday,
            'cash_month'            => $cashMonth,
            'bank_month'            => $bankMonth,
        ];
    }

    public function ticketsByBarber(int $branchId, string $from, string $to): Collection
    {
        return Ticket::where('barbershop_tickets.branch_id', $branchId)
            ->where('barbershop_tickets.status', 'confirmed')
            ->whereBetween('ticket_date', [$from, $to])
            ->join('core_persons', 'barbershop_tickets.profile_barber_id', '=', 'core_persons.id')
            ->select(
                DB::raw("CONCAT(core_persons.name, ' ', core_persons.paternal_surname) as barber"),
                DB::raw('COUNT(*) as tickets'),
                DB::raw('SUM(barbershop_tickets.total) as revenue')
            )
            ->groupBy('barbershop_tickets.profile_barber_id', 'core_persons.name', 'core_persons.paternal_surname')
            ->orderByDesc('revenue')
            ->get();
    }

    public function topServices(int $branchId, string $from, string $to, int $limit = 8): Collection
    {
        return TicketService::join('barbershop_tickets', 'barbershop_ticket_services.ticket_id', '=', 'barbershop_tickets.id')
            ->join('barbershop_services', 'barbershop_ticket_services.service_id', '=', 'barbershop_services.id')
            ->where('barbershop_tickets.branch_id', $branchId)
            ->where('barbershop_tickets.status', 'confirmed')
            ->whereBetween('barbershop_tickets.ticket_date', [$from, $to])
            ->select(
                'barbershop_services.name as service',
                DB::raw('SUM(barbershop_ticket_services.quantity) as quantity'),
                DB::raw('SUM(barbershop_ticket_services.amount) as revenue')
            )
            ->groupBy('barbershop_ticket_services.service_id', 'barbershop_services.name')
            ->orderByDesc('quantity')
            ->limit($limit)
            ->get();
    }

    public function revenueByDay(int $branchId, string $from, string $to): Collection
    {
        return Ticket::where('branch_id', $branchId)
            ->where('status', 'confirmed')
            ->whereBetween('ticket_date', [$from, $to])
            ->select(
                DB::raw("DATE_FORMAT(ticket_date, '%Y-%m-%d') as day"),
                DB::raw('COUNT(*) as tickets'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy(DB::raw("DATE_FORMAT(ticket_date, '%Y-%m-%d')"))
            ->orderBy('day')
            ->get();
    }

    public function recentTickets(int $branchId, int $limit = 10): Collection
    {
        return Ticket::with([
                'barber.person:id,name,paternal_surname',
                'client.person:id,name,paternal_surname',
            ])
            ->where('branch_id', $branchId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(fn($t) => [
                'id'     => $t->id,
                'barber' => $t->barber?->person?->full_name,
                'client' => $t->client?->person?->full_name,
                'total'  => (float) $t->total,
                'status' => $t->status,
                'date'   => $t->ticket_date?->format('Y-m-d\TH:i:s'),
            ]);
    }

    /**
     * Breakdown of income by payment method for the given date range.
     * Returns: name, type (cash|bank), amount, percentage
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
        return DB::table('treasury_income_payment_methods as tipm')
            ->join('treasury_incomes as ti', 'tipm.income_id', '=', 'ti.id')
            ->join('core_payment_methods as cpm', 'tipm.payment_method_id', '=', 'cpm.id')
            ->join('barbershop_tickets as bt', function ($join) {
                $join->on('ti.transactionable_id', '=', 'bt.id')
                     ->where('ti.transactionable_type', '=', 'barbershop_tickets');
            })
            ->where('bt.branch_id', $branchId)
            ->where('bt.status', 'confirmed')
            ->whereBetween('bt.ticket_date', [$from, $to])
            ->select('cpm.name', 'cpm.type', DB::raw('SUM(tipm.amount) as amount'))
            ->groupBy('cpm.id', 'cpm.name', 'cpm.type')
            ->orderByDesc('amount')
            ->get();
    }

    // ─── Finance Dashboard ────────────────────────────────────────────────────

    public function financeSummary(int $branchId, int $infrastructureId, string $from, string $to): array
    {
        $revenueByType = $this->paymentBreakdownBase($branchId, $from, $to)
            ->pluck('amount', 'type');
        $cashRevenue = (float) ($revenueByType['cash'] ?? 0);
        $bankRevenue = (float) ($revenueByType['bank'] ?? 0);

        $revenue = (float) Ticket::where('branch_id', $branchId)
            ->where('status', 'confirmed')
            ->whereBetween('ticket_date', [$from, $to])
            ->sum('total');

        $expenses = (float) Expense::where('infrastructure_id', $infrastructureId)
            ->whereIn('status', ['pending', 'approved'])
            ->whereBetween('transaction_date', [$from, $to])
            ->sum('amount');

        $barberPayments = (float) DB::table('treasury_employee_payments as tep')
            ->join('profile_barbers as pb', 'pb.id', '=', 'tep.employee_id')
            ->where('tep.employee_type', 'profile_barbers')
            ->where('pb.branch_id', $branchId)
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

        $pendingBarberAdvances = (float) DB::table('treasury_employee_advances as tea')
            ->join('profile_barbers as pb', 'pb.id', '=', 'tea.employee_id')
            ->where('tea.employee_type', 'profile_barbers')
            ->where('pb.branch_id', $branchId)
            ->where('tea.status', 'pending')
            ->sum('tea.amount');

        $pendingWorkerAdvances = (float) DB::table('treasury_employee_advances as tea')
            ->join('profile_workers as pw', 'pw.id', '=', 'tea.employee_id')
            ->where('tea.employee_type', 'profile_workers')
            ->where('pw.infrastructure_id', $infrastructureId)
            ->where('tea.status', 'pending')
            ->sum('tea.amount');

        $totalPayments    = $barberPayments + $workerPayments;
        $totalEgress      = $expenses + $totalPayments;
        $netFlow          = $revenue - $totalEgress;
        $pendingAdvances  = $pendingBarberAdvances + $pendingWorkerAdvances;

        return [
            'revenue'         => $revenue,
            'cashRevenue'     => $cashRevenue,
            'bankRevenue'     => $bankRevenue,
            'expenses'        => $expenses,
            'barberPayments'  => $barberPayments,
            'workerPayments'  => $workerPayments,
            'totalPayments'   => $totalPayments,
            'totalEgress'     => $totalEgress,
            'netFlow'         => $netFlow,
            'pendingAdvances' => $pendingAdvances,
        ];
    }

    public function cashFlow(int $branchId, int $infrastructureId, string $from, string $to): array
    {
        $revenueByDay = Ticket::where('branch_id', $branchId)
            ->where('status', 'confirmed')
            ->whereBetween('ticket_date', [$from, $to])
            ->select(
                DB::raw("DATE_FORMAT(ticket_date, '%Y-%m-%d') as day"),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy(DB::raw("DATE_FORMAT(ticket_date, '%Y-%m-%d')"))
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
        $barbers = DB::table('treasury_employee_payments as tep')
            ->join('profile_barbers as pb', 'pb.id', '=', 'tep.employee_id')
            ->join('core_persons as cp', 'cp.id', '=', 'pb.id')
            ->where('tep.employee_type', 'profile_barbers')
            ->where('pb.branch_id', $branchId)
            ->where('tep.status', '!=', 'cancelled')
            ->whereBetween('tep.payment_date', [$from, $to])
            ->select(
                DB::raw("CONCAT(cp.name, ' ', cp.paternal_surname) as employee"),
                DB::raw("'Barbero' as role"),
                DB::raw('COUNT(*) as payments'),
                DB::raw('SUM(tep.base_amount) as base_amount'),
                DB::raw('SUM(tep.deductions) as deductions'),
                DB::raw('SUM(tep.total_amount) as total_amount')
            )
            ->groupBy('tep.employee_id', 'cp.name', 'cp.paternal_surname')
            ->get()
            ->map(fn($r) => [
                'employeeName'      => $r->employee,
                'paymentsCount'     => (int)   $r->payments,
                'baseAmount'        => (float) $r->base_amount,
                'deductionsAmount'  => (float) $r->deductions,
                'totalPaid'         => (float) $r->total_amount,
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
                DB::raw("'Trabajador' as role"),
                DB::raw('COUNT(*) as payments'),
                DB::raw('SUM(tep.base_amount) as base_amount'),
                DB::raw('SUM(tep.deductions) as deductions'),
                DB::raw('SUM(tep.total_amount) as total_amount')
            )
            ->groupBy('tep.employee_id', 'cp.name', 'cp.paternal_surname')
            ->get()
            ->map(fn($r) => [
                'employeeName'      => $r->employee,
                'paymentsCount'     => (int)   $r->payments,
                'baseAmount'        => (float) $r->base_amount,
                'deductionsAmount'  => (float) $r->deductions,
                'totalPaid'         => (float) $r->total_amount,
            ])
            ->toArray();

        return ['barbers' => $barbers, 'workers' => $workers];
    }

    public function pendingAdvances(int $branchId, int $infrastructureId): array
    {
        $barber = DB::table('treasury_employee_advances as tea')
            ->join('profile_barbers as pb', 'pb.id', '=', 'tea.employee_id')
            ->join('core_persons as cp', 'cp.id', '=', 'pb.id')
            ->where('tea.employee_type', 'profile_barbers')
            ->where('pb.branch_id', $branchId)
            ->where('tea.status', 'pending')
            ->select(
                'tea.id',
                DB::raw("CONCAT(cp.name, ' ', cp.paternal_surname) as employee"),
                DB::raw("'Barbero' as role"),
                'tea.amount',
                'tea.advance_date',
                'tea.reason'
            )
            ->get();

        $worker = DB::table('treasury_employee_advances as tea')
            ->join('profile_workers as pw', 'pw.id', '=', 'tea.employee_id')
            ->join('core_persons as cp', 'cp.id', '=', 'pw.id')
            ->where('tea.employee_type', 'profile_workers')
            ->where('pw.infrastructure_id', $infrastructureId)
            ->where('tea.status', 'pending')
            ->select(
                'tea.id',
                DB::raw("CONCAT(cp.name, ' ', cp.paternal_surname) as employee"),
                DB::raw("'Trabajador' as role"),
                'tea.amount',
                'tea.advance_date',
                'tea.reason'
            )
            ->get();

        return $barber->concat($worker)
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
}