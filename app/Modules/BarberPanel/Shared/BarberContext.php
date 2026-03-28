<?php

namespace App\Modules\BarberPanel\Shared;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Profile;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class BarberContext
{
    public static function barberId(): int
    {
        $profileId = JWTAuth::parseToken()->getPayload()->get('prf');
        $profile = Profile::find($profileId);

        if (!$profile || $profile->profileable_type !== 'profile_barbers') {
            throw new ApiException('Perfil de barbero no encontrado', 403);
        }

        return $profile->profileable_id;
    }
}
