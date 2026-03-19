<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Profile\Teacher;
use Illuminate\Support\Facades\DB;

class TeacherRepository
{
    public function dataTable($request)
    {
        $items = Teacher::select(
            //profile_teachers id |  person id
            'profile_teachers.core_person_id as id',

            //teacher
            'profile_teachers.branch_id',
            'profile_teachers.is_active',

            //branch
            'academy_branches.name as branch_name',

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
            ->join('core_persons', 'profile_teachers.core_person_id', '=', 'core_persons.id')
            ->join('behavior_profiles', function ($join) {
                $join->on('profile_teachers.core_person_id', '=', 'behavior_profiles.profileable_id')
                    ->where('behavior_profiles.profileable_type', 'profile_teachers');
            })
            ->join('auth_users', 'behavior_profiles.auth_user_id', '=', 'auth_users.id')
            ->leftJoin('academy_branches', 'profile_teachers.branch_id', '=', 'academy_branches.id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('profile_teachers.core_person_id', 'desc');
        }

        $items = $items->dataTable($request);
        return $items;
    }

    public function findByPersonId(int $personId): ?Teacher
    {
        return Teacher::where('core_person_id', $personId)->first();
    }

    public function create(int $personId, ?int $branchId = null, bool $isActive = true): Teacher
    {
        return Teacher::create([
            'core_person_id' => $personId,
            'branch_id' => $branchId,
            'is_active' => $isActive,
        ]);
    }

    public function update(Teacher $teacher, ?int $branchId, bool $isActive): Teacher
    {
        $teacher->update([
            'branch_id' => $branchId,
            'is_active' => $isActive,
        ]);
        return $teacher;
    }

    public function selectAsyncItems($search)
    {
        $items = Teacher::select(
            'profile_teachers.core_person_id as id',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
        )
            ->join('core_persons', 'profile_teachers.core_person_id', '=', 'core_persons.id');

        if (!empty($search)) {
            $items->where(function ($query) use ($search) {
                $query->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.maternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%")
                    ->orWhere(DB::raw('CONCAT(core_persons.name, " ", core_persons.paternal_surname, " ", core_persons.maternal_surname)'), 'like', "%{$search}%");
            });
        }

        return $items->limit(100)->get();
    }
}
