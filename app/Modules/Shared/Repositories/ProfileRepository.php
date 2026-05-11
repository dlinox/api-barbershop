<?php

namespace App\Modules\Shared\Repositories;

use App\Models\Behavior\Profile;

class ProfileRepository
{
    public function create(int $userId, string $profileType, int $profileId, $roleId): Profile
    {
        return Profile::create([
            'auth_user_id' => $userId,
            'profileable_type' => 'profile_' . $profileType,
            'profileable_id' => $profileId,
            'behavior_role_id' => $roleId,
        ]);
    }

    public function findByProfileableId(int $profileableId): ?Profile
    {
        return Profile::where('profileable_id', $profileableId)->first();
    }

    public function findByProfileableIdAndType(int $profileableId, string $type): ?Profile
    {
        $typeTable = 'profile_' . $type;
        return Profile::where('profileable_id', $profileableId)
            ->where('profileable_type', $typeTable)
            ->first();
    }

    public function findUserIdAndType(int $userId, string $type): ?Profile
    {
        $typeTable = 'profile_' . $type;
        return Profile::where('auth_user_id', $userId)
            ->where('profileable_type', $typeTable)
            ->first();
    }
}
