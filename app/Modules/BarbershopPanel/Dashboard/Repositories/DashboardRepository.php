<?php

namespace App\Modules\BarbershopPanel\Dashboard\Repositories;

use App\Models\Barbershop\Ticket;
use App\Models\Profile\Barber;
use App\Models\Barbershop\TicketService;
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
            ->where('ticket_date', $today)
            ->where('status', 'confirmed')
            ->count();

        $ticketsMonth = Ticket::where('branch_id', $branchId)
            ->whereBetween('ticket_date', [$startOfMonth, $endOfMonth])
            ->where('status', 'confirmed')
            ->count();

        $revenueToday = (float) Ticket::where('branch_id', $branchId)
            ->where('ticket_date', $today)
            ->where('status', 'confirmed')
            ->sum('total');

        $revenueMonth = (float) Ticket::where('branch_id', $branchId)
            ->whereBetween('ticket_date', [$startOfMonth, $endOfMonth])
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
                'date'   => $t->ticket_date,
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
}