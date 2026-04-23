<?php

namespace App\Modules\BarbershopPanel\Barbershop\Repositories;

use App\Models\Barbershop\Reservation;
use App\Common\Http\Context\AdminContext;

class ReservationRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::barbershopBranchId();

        $query = Reservation::query()
            ->join('barbershop_branches', 'barbershop_branches.id', '=', 'barbershop_reservations.branch_id')
            ->join('profile_clients', 'profile_clients.id', '=', 'barbershop_reservations.profile_client_id')
            ->join('core_persons', 'core_persons.id', '=', 'profile_clients.id')
            ->leftJoin('barbershop_services', 'barbershop_services.id', '=', 'barbershop_reservations.service_id')
            ->select(
                'barbershop_reservations.*',
                'barbershop_branches.name as branch_name',
                'core_persons.name as client_name',
                'core_persons.paternal_surname as client_paternal_surname',
                'core_persons.maternal_surname as client_maternal_surname',
                'barbershop_services.name as service_name',
            )
            ->where('barbershop_reservations.branch_id', $branchId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('barbershop_reservations.id', 'desc');
        }

        return Reservation::scopeDataTable($query, $request, [
            'core_persons.name',
            'core_persons.paternal_surname',
            'core_persons.document_number',
            'barbershop_branches.name',
            'barbershop_services.name',
            'barbershop_reservations.date',
            'barbershop_reservations.status',
        ]);
    }

    public function createOrUpdate(array $data)
    {
        $data['branch_id'] = AdminContext::barbershopBranchId();
        return Reservation::updateOrCreate(['id' => $data['id'] ?? null], $data);
    }

    public function delete(int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return $reservation;
    }
}