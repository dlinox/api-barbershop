<?php

namespace App\Modules\BarbershopPanel\Barbershop\Repositories;

use App\Models\Profile\Barber;
use App\Common\Http\Context\AdminContext;

class BarberAttendanceRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::barbershopBranchId();
        $date = $request->date ?? date('Y-m-d');

        $query = Barber::select(
            'profile_barbers.id as barber_id',
            'profile_barbers.branch_id as barber_branch_id',
            'core_persons.id as barber_person_id',
            'core_persons.name as barber_name',
            'core_persons.paternal_surname as barber_paternal_surname',
            'core_persons.maternal_surname as barber_maternal_surname',
            'core_persons.document_number as barber_document',
            'barbershop_branches.name as barber_branch_name',
            'barber_attendances.id as attendance_id',
            'barber_attendances.date as attendance_date',
            'barber_attendances.check_in',
            'barber_attendances.check_out',
            'barber_attendances.check_token',
            'barber_attendances.check_type',
            'barber_attendances.status as attendance_status',
            'barber_attendances.observation',
        )
            ->join('core_persons', 'profile_barbers.id', '=', 'core_persons.id')
            ->join('barbershop_branches', 'profile_barbers.branch_id', '=', 'barbershop_branches.id')
            ->leftJoin('barber_attendances', function ($join) use ($date) {
                $join->on('barber_attendances.barber_id', '=', 'profile_barbers.id')
                    ->where('barber_attendances.date', '=', $date);
            })
            ->where('profile_barbers.is_active', true)
            ->where('profile_barbers.branch_id', $branchId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('core_persons.name', 'asc');
        }

        return $query->dataTable($request);
    }
}