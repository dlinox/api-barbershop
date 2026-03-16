<?php

namespace App\Modules\Administrator\Barbershop\Repositories;

use App\Models\Barbershop\Ticket;
use Illuminate\Http\Request;

class TicketRepository
{
    public function dataTable(Request $request)
    {
        $query = Ticket::select(
            'barbershop_tickets.*',
            'core_persons.name as client_name',
            'core_persons.paternal_surname as client_paternal_surname',
            // 'barber_persons.name as worker_name',
            // 'barber_persons.paternal_surname as worker_paternal_surname'
        )->join('treasury_cash_sessions', 'treasury_cash_sessions.id', 'barbershop_tickets.cash_session_id')
            ->join('treasury_cash_registers', 'treasury_cash_registers.id', 'treasury_cash_sessions.cash_register_id')
            ->leftJoin('core_persons', 'core_persons.id', 'barbershop_tickets.profile_client_id');
            // ->leftJoin('core_persons as barber_persons', 'barber_persons.id', 'barbershop_tickets.profile_barber_id');


        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('barbershop_tickets.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findById(int $id): Ticket
    {
        return Ticket::with([
            'services.serviceBranch.service.category',
            'sale.items.presentation.product',
        ])->findOrFail($id);
    }
}
