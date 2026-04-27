<?php

namespace App\Modules\BarbershopPanel\Barbershop\Repositories;

use App\Models\Profile\Client;
use Illuminate\Support\Facades\DB;

class ClientRepository
{
    public function dataTable($request)
    {
        $items = Client::select(
            'profile_clients.id as id',
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
            ->join('core_persons', 'profile_clients.id', '=', 'core_persons.id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('profile_clients.id', 'desc');
        }

        return $items->dataTable($request);
    }

    public function selectAsyncItems($search, $value = null)
    {
        $selected = null;
        $limit = 25;

        $items = Client::select(
            'profile_clients.id as id',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
        )
            ->join('core_persons', 'profile_clients.id', '=', 'core_persons.id');

        if (!empty($value)) {
            $selected = (clone $items)->where('profile_clients.id', $value)->first();
            if ($selected) {
                $limit = 24;
            }
        }

        if (!empty($search)) {
            $items->where(function ($query) use ($search) {
                $query->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%")
                    ->orWhere(DB::raw('CONCAT(core_persons.name, " ", core_persons.paternal_surname, " ", core_persons.maternal_surname)'), 'like', "%{$search}%");
            });
        }

        if ($selected) {
            $items->where('profile_clients.id', '!=', $value);
        }

        $items = $items->limit($limit)->get();

        if ($selected) {
            $items->prepend($selected);
        }

        return $items;
    }
}
