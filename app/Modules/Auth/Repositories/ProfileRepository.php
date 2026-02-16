<?php

namespace App\Modules\Auth\Repositories;

use Illuminate\Support\Collection;
use App\Models\Behavior\Profile;

class ProfileRepository
{
    public function getByUserId(int $userId): Collection
    {
        return Profile::select(
            'behavior_profiles.id',
            'behavior_roles.display_name as role_name'
        )
            ->join('behavior_roles', 'behavior_roles.id', '=', 'behavior_profiles.behavior_role_id')
            ->where('behavior_profiles.auth_user_id', $userId)
            ->where('behavior_profiles.is_active', true)
            ->get();
    }

    public function findProfile(int $userId, int $profileId, string $profileType): ?Profile
    {
        return Profile::where('auth_user_id', $userId)
            ->where('profileable_id', $profileId)
            ->where('profileable_type', $profileType)
            ->first();
    }

    public function countByUserId(int $userId): int
    {
        return Profile::where('auth_user_id', $userId)
            ->where('is_active', true)
            ->count();
    }

    public function firstActiveByUserId(int $userId): ?Profile
    {
        return Profile::where('auth_user_id', $userId)
            ->where('is_active', true)
            ->first();
    }

    public function findById(int $profileId): ?Profile
    {
        return Profile::where('id', $profileId)
            ->where('is_active', true)
            ->first();
    }
}
