<?php

namespace App\Modules\BarbershopPanel\Barbershop\Repositories;

use App\Models\Profile\Barber;
use App\Common\Http\Context\AdminContext;

class BarberRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::barbershopBranchId();

        $items = Barber::select(
            'profile_barbers.id as id',
            'profile_barbers.branch_id',
            'profile_barbers.commission_percentage',
            'profile_barbers.is_active',
            'barbershop_branches.name as branch_name',
            'core_persons.document_type as person_document_type',
            'core_persons.document_number as person_document_number',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.email as person_email',
            'core_persons.phone as person_phone',
            'core_persons.date_birth as person_date_birth',
            'core_persons.gender as person_gender',
            'core_persons.address as person_address',
        )
            ->join('core_persons', 'profile_barbers.id', '=', 'core_persons.id')
            ->join('barbershop_branches', 'profile_barbers.branch_id', '=', 'barbershop_branches.id')
            ->where('profile_barbers.branch_id', $branchId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('profile_barbers.id', 'desc');
        }

        return $items->dataTable($request);
    }

    public function selectAsyncItems($search, $value = null)
    {
        $branchId = AdminContext::barbershopBranchId();
        $selected = null;
        $limit = 25;

        $items = Barber::select(
            'profile_barbers.id as id',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
        )
            ->join('core_persons', 'profile_barbers.id', '=', 'core_persons.id')
            ->where('profile_barbers.branch_id', $branchId)
            ->where('profile_barbers.is_active', true);

        if (!empty($value)) {
            $selected = (clone $items)->where('profile_barbers.id', $value)->first();
            if ($selected) {
                $limit = 24;
            }
        }

        if (!empty($search)) {
            $items->where(function ($q) use ($search) {
                $q->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%");
            });
        }

        if ($selected) {
            $items->where('profile_barbers.id', '!=', $value);
        }

        $items = $items->limit($limit)->get();

        if ($selected) {
            $items->prepend($selected);
        }

        return $items;
    }

    public function detail(int $id): Barber
    {
        return Barber::with([
            'person.documentTypeRelation',
            'person.genderRelation',
            'branch',
        ])->findOrFail($id);
    }
}
