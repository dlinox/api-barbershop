<?php

namespace App\Modules\Administrator\Barbershop\Repositories;

use App\Models\Barbershop\Ticket;
use Illuminate\Http\Request;

class TicketRepository
{
    public function dataTable(Request $request, int $branchId)
    {
        $query = Ticket::select(
            'barbershop_tickets.*',
            'core_persons.name as client_name',
            'core_persons.paternal_surname as client_paternal_surname',
            'core_persons.maternal_surname as client_maternal_surname',
            // 'auth_users.username as user_username',
        )->join('treasury_cash_sessions', 'treasury_cash_sessions.id', 'barbershop_tickets.cash_session_id')
            ->join('treasury_cash_registers', 'treasury_cash_registers.id', 'treasury_cash_sessions.cash_register_id')
            ->leftJoin('core_persons', 'core_persons.id', 'barbershop_tickets.profile_client_id');
        // ->leftJoin('auth_users', 'auth_users.id', 'barbershop_tickets.user_id');


        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('barbershop_tickets.id', 'desc');
        }

        $items = $query->dataTable($request);

        return $items;
    }

    public function findById(int $id): Ticket
    {
        return Ticket::with([
            'services.serviceBranch.service',
            'sale.items.presentation',
        ])->findOrFail($id);
    }
}
