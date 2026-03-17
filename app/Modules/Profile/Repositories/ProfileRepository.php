<?php

namespace App\Modules\Profile\Repositories;

use App\Models\Auth\User;
use App\Models\Behavior\Profile;
use App\Models\Core\Person;
use Illuminate\Support\Collection;

class ProfileRepository
{
    public function getPersonByUserId(int $userId): ?Person
    {
        $profile = Profile::where('auth_user_id', $userId)
            ->where('is_active', true)
            ->first();

        if (!$profile) return null;

        return Person::find($profile->profileable_id);
    }

    public function getAllProfilesByUserId(int $userId): Collection
    {
        return Profile::select(
            'behavior_profiles.id',
            'behavior_profiles.profileable_type',
            'behavior_profiles.is_active',
            'behavior_roles.display_name as role_name',
            'behavior_roles.level as role_level',
        )
            ->join('behavior_roles', 'behavior_roles.id', '=', 'behavior_profiles.behavior_role_id')
            ->where('behavior_profiles.auth_user_id', $userId)
            ->get();
    }
}
