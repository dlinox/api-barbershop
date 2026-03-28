<?php

namespace App\Modules\BarberPanel\Dashboard\Repositories;

use App\Models\Barbershop\BarberAttendance;
use App\Models\Barbershop\Ticket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function summary(int $barberId): array
    {
        $today = date('Y-m-d');
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $ticketsToday = Ticket::where('profile_barber_id', $barberId)
            ->where('ticket_date', $today)
            ->where('status', 'confirmed')
            ->count();

        $ticketsMonth = Ticket::where('profile_barber_id', $barberId)
            ->whereBetween('ticket_date', [$startOfMonth, $endOfMonth])
            ->where('status', 'confirmed')
            ->count();

        $revenueMonth = (float) Ticket::where('profile_barber_id', $barberId)
            ->whereBetween('ticket_date', [$startOfMonth, $endOfMonth])
            ->where('status', 'confirmed')
            ->sum('total');

        $attendanceRate = $this->computeAttendanceRate($barberId);

        return [
            'tickets_today'  => $ticketsToday,
            'tickets_month'  => $ticketsMonth,
            'revenue_month'  => $revenueMonth,
            'attendance_rate' => $attendanceRate,
        ];
    }

    public function recentTickets(int $barberId, int $limit = 5): Collection
    {
        return Ticket::select(
            'barbershop_tickets.id',
            'barbershop_tickets.ticket_number',
            'barbershop_tickets.total',
            'barbershop_tickets.ticket_date',
            'barbershop_tickets.status',
            'core_persons.name as client_name',
            'core_persons.paternal_surname as client_paternal_surname',
        )
            ->leftJoin('core_persons', 'barbershop_tickets.profile_client_id', '=', 'core_persons.id')
            ->where('barbershop_tickets.profile_barber_id', $barberId)
            ->orderByDesc('barbershop_tickets.ticket_date')
            ->orderByDesc('barbershop_tickets.id')
            ->limit($limit)
            ->get()
            ->map(fn($t) => [
                'id'         => $t->id,
                'number'     => $t->ticket_number,
                'client'     => $t->client_name
                    ? trim($t->client_paternal_surname . ' ' . $t->client_name)
                    : 'Público general',
                'total'      => (float) $t->total,
                'date'       => $t->ticket_date,
                'status'     => $t->status,
            ]);
    }

    public function recentAttendance(int $barberId, int $limit = 5): Collection
    {
        return BarberAttendance::select(
            'barber_attendances.id',
            'barber_attendances.date',
            'barber_attendances.check_in',
            'barber_attendances.check_out',
            'barber_attendances.status',
        )
            ->where('barber_attendances.barber_id', $barberId)
            ->orderByDesc('barber_attendances.date')
            ->limit($limit)
            ->get()
            ->map(fn($a) => [
                'id'        => $a->id,
                'date'      => $a->date->format('Y-m-d'),
                'check_in'  => $a->check_in ? substr($a->check_in, 0, 5) : null,
                'check_out' => $a->check_out ? substr($a->check_out, 0, 5) : null,
                'status'    => $a->status,
            ]);
    }

    private function computeAttendanceRate(int $barberId): float
    {
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $stats = BarberAttendance::where('barber_id', $barberId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status IN ('present', 'late', 'late_justified') THEN 1 ELSE 0 END) as attended")
            )
            ->first();

        if (!$stats || $stats->total == 0) return 0;

        return round(($stats->attended / $stats->total) * 100, 1);
    }
}
