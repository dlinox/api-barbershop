<?php

namespace App\Modules\TeacherPanel\Shared;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Profile;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TeacherContext
{
    public static function teacherId(): int
    {
        $profileId = JWTAuth::parseToken()->getPayload()->get('prf');
        $profile = Profile::find($profileId);

        if (!$profile || $profile->profileable_type !== 'profile_teachers') {
            throw new ApiException('Perfil de docente no encontrado', 403);
        }

        return $profile->profileable_id;
    }
}
