<?php

namespace App\Modules\Administrator\Security\Repositories;

use App\Models\Profile\Admin;

class AdminRepository
{

    public function dataTable($request)
    {
        $items = Admin::select(
            //profile_admins id |  person id
            'profile_admins.core_person_id',
            'profile_admins.core_person_id as id',

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

            //user
            'auth_users.id as user_id',
            'auth_users.username as user_username',
            'auth_users.email as user_email',
            'auth_users.is_active as user_is_active',

            //role
            'behavior_roles.id as role_id',
            'behavior_roles.display_name as role_display_name',
        )
            ->join('core_persons', 'profile_admins.core_person_id', '=', 'core_persons.id')
            ->join('behavior_profiles', 'profile_admins.core_person_id', '=', 'behavior_profiles.profileable_id')
            ->join('auth_users', 'behavior_profiles.auth_user_id', '=', 'auth_users.id')
            ->join('behavior_roles', 'behavior_roles.id', '=', 'behavior_profiles.behavior_role_id')
            ->where('behavior_roles.level', '1')
            ->with('infrastructures');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('profile_admins.core_person_id', 'desc');
        }

        $items = $items->dataTable($request);
        return $items;
    }

    public function findByPersonId(int $personId): ?Admin
    {
        return Admin::where('core_person_id', $personId)->first();
    }

    public function create(int $personId): Admin
    {
        return Admin::create([
            'core_person_id' => $personId,
        ]);
    }
}
