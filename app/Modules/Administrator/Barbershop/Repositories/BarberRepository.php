<?php

namespace App\Modules\Administrator\Barbershop\Repositories;

use App\Models\Profile\Barber;
use Illuminate\Support\Facades\DB;

class BarberRepository
{
    public function dataTable($request)
    {
        $items = Barber::select(
            //profile_barbers id |  person id
            'profile_barbers.id as id',
            'profile_barbers.branch_id',
            'profile_barbers.commission_percentage',
            'profile_barbers.is_active',

            //branch
            'barbershop_branches.name as branch_name',

            //person
            'core_persons.document_type as person_document_type',
            'core_persons.document_number as person_document_number',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.email as person_email',
            'core_persons.phone as person_phone',

            //user
            'auth_users.id as user_id',
            'auth_users.username as user_username',
            'auth_users.email as user_email',
            'auth_users.is_active as user_is_active',
        )
            ->join('core_persons', 'profile_barbers.id', '=', 'core_persons.id')
            ->join('behavior_profiles', 'profile_barbers.id', '=', 'behavior_profiles.profileable_id')
            ->join('auth_users', 'behavior_profiles.auth_user_id', '=', 'auth_users.id')
            ->join('barbershop_branches', 'profile_barbers.branch_id', '=', 'barbershop_branches.id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('profile_barbers.id', 'desc');
        }

        $items = $items->dataTable($request);
        return $items;
    }

    public function findByPersonId(int $personId): ?Barber
    {
        return Barber::where('id', $personId)->first();
    }

    public function create(int $personId, array $data): Barber
    {
        return Barber::create([
            'id' => $personId,
            'branch_id' => $data['branch_id'],
            'commission_percentage' => $data['commission_percentage'],
            // 'is_active' => $data['is_active'],
        ]);
    }

    public function update(int $personId, array $data): void
    {
        Barber::where('id', $personId)->update([
            'branch_id' => $data['branch_id'],
            'commission_percentage' => $data['commission_percentage'],
            'is_active' => $data['is_active'],
        ]);
    }

    public function selectAsyncItems($search, $value = null, $infrastructureId = null)
    {
        $selected = null;
        $limit = 25;

        $query = Barber::select(
            'profile_barbers.id as id',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
        )
            ->join('core_persons', 'profile_barbers.id', '=', 'core_persons.id');

        if (!empty($infrastructureId)) {
            $query->join('core_infrastructures', function ($join) use ($infrastructureId) {
                $join->on('profile_barbers.branch_id', '=', 'core_infrastructures.infrastructurable_id')
                    ->where('core_infrastructures.infrastructurable_type', 'barbershop_branches')
                    ->where('core_infrastructures.id', $infrastructureId);
            });
        }

        if (!empty($value)) {
            $selected = (clone $query)->where('profile_barbers.id', $value)->first();
            if ($selected) {
                $limit = 24;
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.maternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%")
                    ->orWhere(DB::raw('CONCAT(core_persons.name, " ", core_persons.paternal_surname, " ", core_persons.maternal_surname)'), 'like', "%{$search}%");
            });
        }

        if ($selected) {
            $query->where('profile_barbers.id', '!=', $value);
        }

        $items = $query->limit($limit)->get();

        if ($selected) {
            $items->prepend($selected);
        }

        return $items;
    }
}
