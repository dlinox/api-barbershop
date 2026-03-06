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
            ->join('core_persons', 'profile_students.core_person_id', '=', 'core_persons.id')
            ->join('behavior_profiles', 'profile_students.core_person_id', '=', 'behavior_profiles.profileable_id')
            ->join('auth_users', 'behavior_profiles.auth_user_id', '=', 'auth_users.id')
            ->withCount('enrollments')
            ->dataTable($request);
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
