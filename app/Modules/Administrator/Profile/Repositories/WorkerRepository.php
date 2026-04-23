<?php

namespace App\Modules\Administrator\Profile\Repositories;

use App\Models\Profile\Worker;
use App\Common\Traits\HasInfrastructureScope;

class WorkerRepository
{
    use HasInfrastructureScope;

    public function dataTable($request)
    {
        $items = Worker::select(
            'profile_workers.id',
            'profile_workers.infrastructure_id',
            'profile_workers.position',
            'profile_workers.monthly_salary',
            'profile_workers.payment_frequency',
            'profile_workers.is_active',
            'core_infrastructures.id as infra_id',
            'core_persons.document_type as person_document_type',
            'core_persons.document_number as person_document_number',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.email as person_email',
            'core_persons.phone as person_phone',
        )
            ->join('core_persons', 'profile_workers.id', '=', 'core_persons.id')
            ->leftJoin('core_infrastructures', 'profile_workers.infrastructure_id', '=', 'core_infrastructures.id');

        $this->scopeByInfrastructure($items, 'profile_workers.infrastructure_id');

        if (empty($request->sortBy)) {
            $items->orderBy('profile_workers.id', 'desc');
        }

        return $items->dataTable($request);
    }

    public function findByPersonId(int $personId): ?Worker
    {
        return Worker::where('id', $personId)->first();
    }

    public function create(int $personId, array $data): Worker
    {
        return Worker::create([
            'id'                => $personId,
            'infrastructure_id' => $data['infrastructure_id'],
            'position'          => $data['position'] ?? null,
            'monthly_salary'    => $data['monthly_salary'] ?? null,
            'payment_frequency' => $data['payment_frequency'] ?? null,
            'is_active'         => $data['is_active'] ?? true,
        ]);
    }

    public function update(int $personId, array $data): void
    {
        Worker::where('id', $personId)->update([
            'infrastructure_id' => $data['infrastructure_id'],
            'position'          => $data['position'] ?? null,
            'monthly_salary'    => $data['monthly_salary'] ?? null,
            'payment_frequency' => $data['payment_frequency'] ?? null,
            'is_active'         => $data['is_active'] ?? true,
        ]);
    }

    public function delete(int $personId): void
    {
        $worker = Worker::findOrFail($personId);
        $worker->delete();
    }
}
