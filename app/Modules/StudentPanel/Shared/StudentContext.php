<?php

namespace App\Modules\StudentPanel\Shared;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Profile;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class StudentContext
{
    public static function studentId(): int
    {
        $profileId = JWTAuth::parseToken()->getPayload()->get('prf');
        $profile = Profile::find($profileId);

        if (!$profile || $profile->profileable_type !== 'profile_students') {
            throw new ApiException('Perfil de estudiante no encontrado', 403);
        }

        return $profile->profileable_id;
    }
}
