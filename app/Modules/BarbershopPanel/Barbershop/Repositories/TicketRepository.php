<?php

namespace App\Modules\BarbershopPanel\Barbershop\Repositories;

use App\Models\Barbershop\Ticket;
use App\Common\Http\Context\AdminContext;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TicketRepository
{
    public function dataTable(Request $request)
    {
        $branchId = AdminContext::barbershopBranchId();

        $query = Ticket::select(
            'barbershop_tickets.*',
            'core_persons.name as client_name',
            'core_persons.paternal_surname as client_paternal_surname',
            'treasury_incomes.id as income_id',
            'barber_persons.name as barber_name',
            'barber_persons.paternal_surname as barber_paternal_surname',
            'treasury_cash_sessions.status as cash_session_status',
        )
            ->join('treasury_cash_sessions', 'treasury_cash_sessions.id', 'barbershop_tickets.cash_session_id')
            ->join('treasury_cash_registers', 'treasury_cash_registers.id', 'treasury_cash_sessions.cash_register_id')
            ->leftJoin('core_persons', 'core_persons.id', 'barbershop_tickets.profile_client_id')
            ->leftJoin('core_persons as barber_persons', 'barber_persons.id', 'barbershop_tickets.profile_barber_id')
            ->leftJoin('treasury_incomes', function ($join) {
                $join->on('treasury_incomes.transactionable_id', '=', 'barbershop_tickets.id')
                    ->where('treasury_incomes.transactionable_type', '=', 'barbershop_tickets');
            })
            ->where('barbershop_tickets.branch_id', $branchId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('barbershop_tickets.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findById(int $id): Ticket
    {
        return Ticket::with([
            'services.service.category',
            'sale.items.presentation.product',
        ])->findOrFail($id);
    }

    public function ticketsOverview(int $cashSessionId): array
    {
        $stats = Ticket::selectRaw('status, COUNT(*) as count, COALESCE(SUM(total), 0) as amount')
            ->where('cash_session_id', $cashSessionId)
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return [
            'confirmed' => ['count' => (int)($stats['confirmed']->count ?? 0), 'amount' => (float)($stats['confirmed']->amount ?? 0)],
            'pending'   => ['count' => (int)($stats['pending']->count ?? 0),   'amount' => (float)($stats['pending']->amount ?? 0)],
            'cancelled' => ['count' => (int)($stats['cancelled']->count ?? 0), 'amount' => (float)($stats['cancelled']->amount ?? 0)],
        ];
    }

    public function waitingQueue(int $cashSessionId): Collection
    {
        return Ticket::select(
            'barbershop_tickets.id',
            'barbershop_tickets.ticket_number',
            'barbershop_tickets.profile_barber_id',
            'barbershop_tickets.total',
            'barbershop_tickets.created_at',
            'core_persons.name as client_name',
            'core_persons.paternal_surname as client_paternal_surname',
            'barber_persons.name as barber_name',
        )
            ->leftJoin('core_persons', 'core_persons.id', 'barbershop_tickets.profile_client_id')
            ->leftJoin('core_persons as barber_persons', 'barber_persons.id', 'barbershop_tickets.profile_barber_id')
            ->where('barbershop_tickets.cash_session_id', $cashSessionId)
            ->where('barbershop_tickets.status', 'pending')
            ->orderBy('barbershop_tickets.created_at', 'asc')
            ->get();
    }
}