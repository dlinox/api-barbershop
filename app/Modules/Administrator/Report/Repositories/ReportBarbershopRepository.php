<?php

namespace App\Modules\Administrator\Report\Repositories;

use App\Models\Barbershop\Branch;
use App\Models\Barbershop\Ticket;
use App\Models\Treasury\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportBarbershopRepository
{
    public function summary(string $from, string $to, ?int $branchId = null): array
    {
        $ticketQuery = Ticket::where('status', 'confirmed')
            ->whereBetween('ticket_date', [$from, $to])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId));

        $totalTickets = (clone $ticketQuery)->count();
        $totalRevenue = (float) (clone $ticketQuery)->sum('total');
        $avgTicket = $totalTickets > 0 ? round($totalRevenue / $totalTickets, 2) : 0;

        $uniqueClients = (clone $ticketQuery)
            ->whereNotNull('profile_client_id')
            ->distinct('profile_client_id')
            ->count('profile_client_id');

        return [
            'total_revenue'  => $totalRevenue,
            'total_tickets'  => $totalTickets,
            'avg_ticket'     => $avgTicket,
            'unique_clients' => $uniqueClients,
        ];
    }

    public function revenueTrend(string $from, string $to, ?int $branchId = null): array
    {
        $tickets = Ticket::select(
            DB::raw("DATE(ticket_date) as day"),
            DB::raw('SUM(total) as revenue'),
        )
            ->where('status', 'confirmed')
            ->whereBetween('ticket_date', [$from, $to])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('revenue', 'day');

        $start = Carbon::parse($from);
        $end = Carbon::parse($to);
        $categories = [];
        $data = [];

        while ($start->lte($end)) {
            $dateStr = $start->toDateString();
            $categories[] = $start->format('d');
            $data[] = (float) ($tickets[$dateStr] ?? 0);
            $start->addDay();
        }

        return [
            'categories' => $categories,
            'series'     => [['name' => 'Ingresos', 'data' => $data]],
        ];
    }

    public function topBarbers(string $from, string $to, ?int $branchId = null): array
    {
        $barbers = Ticket::select(
            'profile_barbers.id',
            DB::raw("CONCAT(p.name, ' ', p.paternal_surname) as barber_name"),
            DB::raw('COUNT(barbershop_tickets.id) as ticket_count'),
            DB::raw('SUM(barbershop_tickets.total) as revenue'),
        )
            ->join('profile_barbers', 'profile_barbers.id', '=', 'barbershop_tickets.profile_barber_id')
            ->join('core_persons as p', 'p.id', '=', 'profile_barbers.id')
            ->where('barbershop_tickets.status', 'confirmed')
            ->whereBetween('barbershop_tickets.ticket_date', [$from, $to])
            ->when($branchId, fn($q) => $q->where('barbershop_tickets.branch_id', $branchId))
            ->groupBy('profile_barbers.id', 'barber_name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return $barbers->map(fn($b) => [
            'name'    => $b->barber_name,
            'revenue' => (float) $b->revenue,
            'tickets' => (int) $b->ticket_count,
        ])->toArray();
    }

    public function servicesBreakdown(string $from, string $to, ?int $branchId = null): array
    {
        $services = DB::table('barbershop_ticket_services as ts')
            ->join('barbershop_tickets as t', 't.id', '=', 'ts.ticket_id')
            ->join('barbershop_services as s', 's.id', '=', 'ts.service_id')
            ->where('t.status', 'confirmed')
            ->whereBetween('t.ticket_date', [$from, $to])
            ->when($branchId, fn($q) => $q->where('t.branch_id', $branchId))
            ->select(
                's.name as service',
                DB::raw('SUM(ts.quantity) as quantity'),
                DB::raw('SUM(ts.amount) as revenue'),
            )
            ->groupBy('s.id', 's.name')
            ->orderByDesc('quantity')
            ->get();

        return $services->map(fn($s) => [
            'service'  => $s->service,
            'quantity' => (int) $s->quantity,
            'revenue'  => (float) $s->revenue,
        ])->toArray();
    }

    public function ticketsByStatus(string $from, string $to, ?int $branchId = null): array
    {
        $statuses = Ticket::select(
            'status',
            DB::raw('COUNT(*) as count'),
        )
            ->whereBetween('ticket_date', [$from, $to])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'confirmed' => (int) ($statuses['confirmed'] ?? 0),
            'pending'   => (int) ($statuses['pending'] ?? 0),
            'cancelled' => (int) ($statuses['cancelled'] ?? 0),
        ];
    }

    public function hourlyDistribution(string $from, string $to, ?int $branchId = null): array
    {
        $hours = Ticket::select(
            DB::raw('HOUR(ticket_date) as hour'),
            DB::raw('COUNT(*) as count'),
        )
            ->where('status', 'confirmed')
            ->whereBetween('ticket_date', [$from, $to])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour');

        $categories = [];
        $data = [];
        for ($h = 8; $h <= 20; $h++) {
            $label = $h < 12 ? "{$h}am" : ($h === 12 ? '12pm' : ($h - 12) . 'pm');
            $categories[] = $label;
            $data[] = (int) ($hours[$h] ?? 0);
        }

        return [
            'categories' => $categories,
            'data'       => $data,
        ];
    }

    public function serviceDetail(string $from, string $to, ?int $branchId = null): array
    {
        $services = DB::table('barbershop_ticket_services as ts')
            ->join('barbershop_tickets as t', 't.id', '=', 'ts.ticket_id')
            ->join('barbershop_services as s', 's.id', '=', 'ts.service_id')
            ->join('barbershop_categories as c', 'c.id', '=', 's.category_id')
            ->where('t.status', 'confirmed')
            ->whereBetween('t.ticket_date', [$from, $to])
            ->when($branchId, fn($q) => $q->where('t.branch_id', $branchId))
            ->select(
                's.name as service',
                'c.name as category',
                DB::raw('SUM(ts.quantity) as quantity'),
                DB::raw('SUM(ts.amount) as revenue'),
                DB::raw('ROUND(SUM(ts.amount) / SUM(ts.quantity), 2) as avg_price'),
            )
            ->groupBy('s.id', 's.name', 'c.name')
            ->orderByDesc('quantity')
            ->get();

        return $services->map(fn($s) => [
            'service'  => $s->service,
            'category' => $s->category,
            'quantity' => (int) $s->quantity,
            'revenue'  => (float) $s->revenue,
            'avgPrice' => (float) $s->avg_price,
        ])->toArray();
    }
}
