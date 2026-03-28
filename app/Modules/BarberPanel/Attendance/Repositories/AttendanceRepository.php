<?php

namespace App\Modules\BarberPanel\Attendance\Repositories;

use App\Models\Barbershop\BarberAttendance;

class AttendanceRepository
{
    public function dataTable($request, int $barberId)
    {
        $query = BarberAttendance::select(
            'barber_attendances.id',
            'barber_attendances.date',
            'barber_attendances.check_in',
            'barber_attendances.check_out',
            'barber_attendances.status',
            'barber_attendances.observation',

            // branch
            'barbershop_branches.name as branch_name',
        )
            ->leftJoin('barbershop_branches', 'barber_attendances.branch_id', '=', 'barbershop_branches.id')
            ->where('barber_attendances.barber_id', $barberId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('barber_attendances.date', 'desc');
        }

        return $query->dataTable($request, [
            'barbershop_branches.name',
        ]);
    }
}
