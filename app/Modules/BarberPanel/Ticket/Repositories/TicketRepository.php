<?php

namespace App\Modules\BarberPanel\Ticket\Repositories;

use App\Models\Barbershop\Ticket;
use App\Models\Barbershop\Service;
use Illuminate\Support\Collection;

class TicketRepository
{
    public function dataTable($request, int $barberId)
    {
        $query = Ticket::select(
            'barbershop_tickets.id',
            'barbershop_tickets.ticket_number',
            'barbershop_tickets.amount',
            'barbershop_tickets.discount',
            'barbershop_tickets.total',
            'barbershop_tickets.ticket_date',
            'barbershop_tickets.status',

            // client
            'core_persons.name as client_name',
            'core_persons.paternal_surname as client_paternal_surname',

            // branch
            'barbershop_branches.name as branch_name',

            // income
            'treasury_incomes.id as income_id',

            // cash session status
            'treasury_cash_sessions.status as cash_session_status',
        )
            ->leftJoin('core_persons', 'barbershop_tickets.profile_client_id', '=', 'core_persons.id')
            ->leftJoin('barbershop_branches', 'barbershop_tickets.branch_id', '=', 'barbershop_branches.id')
            ->leftJoin('treasury_cash_sessions', 'treasury_cash_sessions.id', '=', 'barbershop_tickets.cash_session_id')
            ->leftJoin('treasury_incomes', function ($join) {
                $join->on('treasury_incomes.transactionable_id', '=', 'barbershop_tickets.id')
                    ->where('treasury_incomes.transactionable_type', '=', 'barbershop_tickets');
            })
            ->where('barbershop_tickets.profile_barber_id', $barberId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('barbershop_tickets.id', 'desc');
        }

        return $query->dataTable($request, [
            'core_persons.name',
            'core_persons.paternal_surname',
            'barbershop_branches.name',
        ]);
    }

    public function ticketsOverview(int $cashSessionId, int $barberId): array
    {
        $stats = Ticket::selectRaw("
            status,
            COUNT(*) as count,
            COALESCE(SUM(total), 0) as amount
        ")
            ->where('cash_session_id', $cashSessionId)
            ->where('profile_barber_id', $barberId)
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return [
            'confirmed' => [
                'count'  => (int) ($stats['confirmed']->count ?? 0),
                'amount' => (float) ($stats['confirmed']->amount ?? 0),
            ],
            'pending' => [
                'count'  => (int) ($stats['pending']->count ?? 0),
                'amount' => (float) ($stats['pending']->amount ?? 0),
            ],
            'cancelled' => [
                'count'  => (int) ($stats['cancelled']->count ?? 0),
                'amount' => (float) ($stats['cancelled']->amount ?? 0),
            ],
            'total' => [
                'count'  => (int) $stats->sum('count'),
                'amount' => (float) $stats->sum('amount'),
            ],
        ];
    }

    public function findById(int $id, int $barberId): Ticket
    {
        return Ticket::with([
            'services.service.category',
            'sale.items.presentation.product',
        ])
            ->where('profile_barber_id', $barberId)
            ->findOrFail($id);
    }

    public function waitingQueue(int $cashSessionId, int $barberId): Collection
    {
        return Ticket::select(
            'barbershop_tickets.id',
            'barbershop_tickets.ticket_number',
            'barbershop_tickets.total',
            'barbershop_tickets.created_at',
            'core_persons.name as client_name',
            'core_persons.paternal_surname as client_paternal_surname',
        )
            ->leftJoin('core_persons', 'core_persons.id', 'barbershop_tickets.profile_client_id')
            ->where('barbershop_tickets.cash_session_id', $cashSessionId)
            ->where('barbershop_tickets.profile_barber_id', $barberId)
            ->where('barbershop_tickets.status', 'pending')
            ->orderBy('barbershop_tickets.ticket_number', 'asc')
            ->get();
    }

    public function getServicesByInfrastructure(int $infrastructureId): Collection
    {
        return Service::select(
            'barbershop_services.id',
            'barbershop_services.name',
            'barbershop_services.category_id',
            'barbershop_categories.name as category_name',
            'barbershop_services.price',
            'barbershop_services.duration',
        )
            ->join('barbershop_categories', 'barbershop_services.category_id', '=', 'barbershop_categories.id')
            ->join('core_infrastructures', function ($join) use ($infrastructureId) {
                $join->on('barbershop_services.branch_id', '=', 'core_infrastructures.infrastructurable_id')
                    ->where('core_infrastructures.infrastructurable_type', 'barbershop_branches')
                    ->where('core_infrastructures.id', $infrastructureId);
            })
            ->where('barbershop_services.is_active', true)
            ->get();
    }
}
