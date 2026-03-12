<?php

namespace App\Modules\Shared\Repositories;

use App\Models\Core\Person;
use Illuminate\Support\Facades\DB;

class PersonRepository
{
    public function selectAsyncItems($search, $value = null)
    {
        $selected = null;
        $limit = 25;


        $query = Person::select(
            'core_persons.id',
            'core_persons.document_type',
            'core_persons.document_number',
            'core_persons.name',
            'core_persons.paternal_surname',
            'core_persons.maternal_surname',
            'core_persons.phone',
            'core_persons.email',
        );

        if (!empty($value)) {
            $selected = (clone $query)->where('core_persons.id', $value)->first();
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
            $query->where('core_persons.id', '!=', $value);
        }

        $items = $query->limit($limit)->get();

        if ($selected) {
            $items->prepend($selected);
        }

        return $items;
    }
}
