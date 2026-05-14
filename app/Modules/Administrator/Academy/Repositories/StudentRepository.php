<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Profile\Student;
use Illuminate\Support\Facades\DB;

class StudentRepository
{
    public function dataTable($request)
    {
        $items = Student::select(
            //profile_students id |  person id
            'profile_students.core_person_id as id',
            'profile_students.is_active as is_active',

            //person
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
            ->join('core_persons', 'profile_students.core_person_id', '=', 'core_persons.id')
            ->withCount('enrollments');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('profile_students.core_person_id', 'desc');
        }

        $items = $items->dataTable($request);
        return $items;
    }

    public function userDataTable($request)
    {
        $items = Student::select(
            'profile_students.core_person_id as id',

            //person
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',

            //user
            'auth_users.id as user_id',
            'auth_users.username as user_username',
            'auth_users.email as user_email',
            'auth_users.is_active as user_is_active',
        )
            ->join('core_persons', 'profile_students.core_person_id', '=', 'core_persons.id')
            ->join('behavior_profiles', function ($join) {
                $join->on('behavior_profiles.profileable_id', '=', 'profile_students.core_person_id')
                    ->where('behavior_profiles.profileable_type', 'profile_students');
            })
            ->join('auth_users', 'behavior_profiles.auth_user_id', '=', 'auth_users.id');

        // Fix: apply is_active with qualified column to avoid ambiguity across joined tables
        $filters = is_array($request->filters) ? $request->filters : [];
        if (array_key_exists('isActive', $filters)) {
            if (!is_null($filters['isActive'])) {
                $items->where('auth_users.is_active', $filters['isActive']);
            }
            $request->merge(['filters' => collect($filters)->except('isActive')->all()]);
        }

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('core_persons.name', 'asc');
        }

        $userSearchColumns = [
            'core_persons.name',
            'core_persons.paternal_surname',
            'core_persons.maternal_surname',
            'auth_users.username',
            'auth_users.email',
        ];

        $items = $items->dataTable($request, $userSearchColumns);
        return $items;
    }

    public function findByPersonId(int $personId): ?Student
    {
        return Student::where('core_person_id', $personId)->first();
    }

    public function create(int $personId): Student
    {
        return Student::create([
            'core_person_id' => $personId,
        ]);
    }

    public function selectAsyncItems($search)
    {
        $items = Student::select(
            'profile_students.core_person_id as id',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
        )
            ->join('core_persons', 'profile_students.core_person_id', '=', 'core_persons.id');

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
