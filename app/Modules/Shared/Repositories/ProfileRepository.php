<?php

namespace App\Modules\Shared\Repositories;

use App\Models\Behavior\Profile;
use App\Common\Exceptions\ApiException;

class ProfileRepository
{
    /**
     * Crea un behavior_profile.
     * Antes de crearlo valida que el usuario no esté ya vinculado a una persona diferente,
     * garantizando la regla: una persona = un solo usuario.
     */
    public function create(int $userId, string $profileType, int $profileId, $roleId): Profile
    {
        // ── Regla: un usuario no puede pertenecer a dos personas distintas ──
        $conflictingProfile = Profile::where('auth_user_id', $userId)
            ->where('profileable_id', '!=', $profileId)
            ->first();

        if ($conflictingProfile) {
            throw new ApiException(
                "El usuario ya está vinculado a otra persona (perfil tipo: {$conflictingProfile->profileable_type}, " .
                "profileable_id: {$conflictingProfile->profileable_id}). " .
                "Una persona solo puede tener un usuario."
            );
        }

        return Profile::create([
            'auth_user_id'      => $userId,
            'profileable_type'  => 'profile_' . $profileType,
            'profileable_id'    => $profileId,
            'behavior_role_id'  => $roleId,
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

