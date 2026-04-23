<?php

namespace App\Modules\Auth\Repositories\Queries;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

use App\Models\Auth\User;
use App\Models\Behavior\Profile;
use App\Models\Core\Infrastructure;
use App\Common\Exceptions\ApiException;

use App\Modules\Auth\Repositories\ProfileRepository;

class MeQuery
{

    public function __construct(
        private ProfileRepository $profileRepository,
    ) {}

    public function __invoke(User $user, ?int $profileId, ?int $infrastructureId = null): array
    {

        if ($profileId) {
            $profile = $this->profileRepository->findById($profileId);
        } else {
            $profile = $this->profileRepository->firstActiveByUserId($user->id);
        }

        if (!$profile) {
            JWTAuth::invalidate(JWTAuth::getToken());
            throw new ApiException("Perfil no encontrado", 404);
        }

        $redirectTo = $this->computeRedirectTo($profile, $infrastructureId);
        $infrastructure = $this->resolveInfrastructure($profile, $infrastructureId);

        return  [
            'name' => collect([$profile->person->name, $profile->person->paternal_surname, $profile->person->maternal_surname])->filter()->implode(' '),
            'username' => $user->username,
            'email' => $user->email,
            'profile' => [
                'id' => $profile->id,
                'role' => $profile->role->display_name,
                'redirectTo' => $redirectTo,
                'roleLevel' => $profile->role->level,
                'permissions' => $profile->role->permissions->pluck('name')->toArray(),
                'infrastructure' => $infrastructure,
            ],
        ];
    }

    private function resolveInfrastructure(Profile $profile, ?int $infrastructureId): ?array
    {
        if ((int) $profile->role->level !== 1) {
            return null;
        }

        if (!$infrastructureId) {
            try {
                $payload = JWTAuth::parseToken()->getPayload();
                $infrastructureId = $payload->get('inf');
            } catch (\Exception) {
                // No JWT context available
            }
        }

        if (!$infrastructureId) {
            return null;
        }

        $infra = Infrastructure::find($infrastructureId);
        if (!$infra) {
            return null;
        }

        return [
            'id'   => $infra->id,
            'name' => $infra->infrastructurable?->name ?? $infra->id,
            'type' => str_contains($infra->infrastructurable_type, 'barbershop') ? 'barbershop' : 'academy',
        ];
    }

    private function computeRedirectTo(Profile $profile, ?int $infrastructureId): string
    {
        if ((int) $profile->role->level !== 1) {
            return $profile->role->redirect_to;
        }

        // For level 1 admins, try parameter then JWT claim
        if (!$infrastructureId) {
            try {
                $payload = JWTAuth::parseToken()->getPayload();
                $infrastructureId = $payload->get('inf');
            } catch (\Exception) {
                // No JWT context available (e.g. during signIn before token is created)
            }
        }

        if (!$infrastructureId) {
            return '/auth/select-infrastructure';
        }

        $infra = Infrastructure::find($infrastructureId);
        if (!$infra) {
            return '/auth/select-infrastructure';
        }

        return str_contains($infra->infrastructurable_type, 'barbershop')
            ? '/barbershop'
            : '/academy';
    }
}
