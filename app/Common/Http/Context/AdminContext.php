<?php

namespace App\Common\Http\Context;

use App\Common\Exceptions\ApiException;
use App\Models\Core\Infrastructure;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AdminContext
{
    public static function infrastructureId(): int
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $infId = $payload->get('inf');

        if (!$infId) {
            throw new ApiException('Sede no seleccionada. Por favor selecciona una sede.', 403);
        }

        return (int) $infId;
    }

    public static function barbershopBranchId(): int
    {
        $infId = self::infrastructureId();

        $infra = Infrastructure::find($infId);

        if (!$infra || !str_contains($infra->infrastructurable_type, 'barbershop')) {
            throw new ApiException('La sede seleccionada no corresponde a una barbería', 403);
        }

        return (int) $infra->infrastructurable_id;
    }

    public static function academyBranchId(): int
    {
        $infId = self::infrastructureId();

        $infra = Infrastructure::find($infId);

        if (!$infra || !str_contains($infra->infrastructurable_type, 'academy')) {
            throw new ApiException('La sede seleccionada no corresponde a una academia', 403);
        }

        return (int) $infra->infrastructurable_id;
    }
}
