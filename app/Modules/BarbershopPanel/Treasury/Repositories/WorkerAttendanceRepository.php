<?php

namespace App\Modules\BarbershopPanel\Treasury\Repositories;

use App\Models\Profile\Worker;
use App\Common\Http\Context\AdminContext;

class WorkerAttendanceRepository
{
    public function dataTable($request)
    {
        $infrastructureId = AdminContext::infrastructureId();
        $date = $request->date ?? date('Y-m-d');

        $query = Worker::select(
            'profile_workers.id as worker_id',
            'core_persons.id as worker_person_id',
            'core_persons.name as worker_name',
            'core_persons.paternal_surname as worker_paternal_surname',
            'core_persons.maternal_surname as worker_maternal_surname',
            'core_persons.document_number as worker_document',
            'worker_attendances.id as attendance_id',
            'worker_attendances.date as attendance_date',
            'worker_attendances.check_in',
            'worker_attendances.check_out',
            'worker_attendances.check_token',
            'worker_attendances.check_type',
            'worker_attendances.status as attendance_status',
            'worker_attendances.observation',
        )
            ->join('core_persons', 'profile_workers.id', '=', 'core_persons.id')
            ->leftJoin('worker_attendances', function ($join) use ($date) {
                $join->on('worker_attendances.worker_id', '=', 'profile_workers.id')
                    ->where('worker_attendances.date', '=', $date);
            })
            ->where('profile_workers.is_active', true)
            ->where('profile_workers.infrastructure_id', $infrastructureId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('core_persons.name', 'asc');
        }

        return $query->dataTable($request);
    }
}
