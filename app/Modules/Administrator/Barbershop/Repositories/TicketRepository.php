<?php

namespace App\Modules\Administrator\Barbershop\Repositories;

use App\Models\Barbershop\Ticket;
use Illuminate\Http\Request;

class TicketRepository
{
    public function dataTable(Request $request, int $branchId)
    {
        $query = Ticket::join('barbershop_branches', 'barbershop_branches.id', '=', 'barbershop_tickets.branch_id')
            ->leftJoin('profile_workers', 'profile_workers.id', '=', 'barbershop_tickets.profile_worker_id')
            ->leftJoin('core_persons as worker_persons', 'worker_persons.id', '=', 'profile_workers.id')
            ->leftJoin('profile_clients', 'profile_clients.id', '=', 'barbershop_tickets.profile_client_id')
            ->leftJoin('core_persons as client_persons', 'client_persons.id', '=', 'profile_clients.id')
            ->where('barbershop_tickets.branch_id', $branchId)
            ->select(
                'barbershop_tickets.*',
                'barbershop_branches.name as branch_name',
                'worker_persons.name as worker_name',
                'worker_persons.paternal_surname as worker_paternal_surname',
                'client_persons.name as client_name',
                'client_persons.paternal_surname as client_paternal_surname',
                'client_persons.maternal_surname as client_maternal_surname',
            );

        $query->orderBy('barbershop_tickets.created_at', 'desc');

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
