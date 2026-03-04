<?php

namespace App\Modules\Administrator\Barbershop\Repositories;

use App\Models\Profile\Worker;
use Illuminate\Support\Facades\DB;

class WorkerRepository
{
    public function dataTable($request)
    {
        $items = Worker::select(
            'profile_workers.id as id',

            //position
            'profile_workers.position as position',

            //person
            'core_persons.document_type as person_document_type',
            'core_persons.document_number as person_document_number',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.email as person_email',
            'core_persons.phone as person_phone',
        )
            ->join('core_persons', 'profile_workers.id', '=', 'core_persons.id')
            ->dataTable($request);
        return $items;
    }

    public function findByPersonId(int $personId): ?Worker
    {
        return Worker::where('id', $personId)->first();
    }

    public function create(int $personId, string $position = 'barber'): Worker
    {
        return Worker::create([
            'id' => $personId,
            'position' => $position,
        ]);
    }

    public function selectAsyncItems($search)
    {
        $items = Worker::select(
            'profile_workers.id as id',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
        )
            ->join('core_persons', 'profile_workers.id', '=', 'core_persons.id');

        if (!empty($search)) {
            $items->where(function ($query) use ($search) {
                $query->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.maternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%")
                    ->orWhere(DB::raw('CONCAT(core_persons.name, " ", core_persons.paternal_surname, " ", core_persons.maternal_surname)'), 'like', "%{$search}%");
            });
        }

        return $items->limit(20)->get();
    }
}
