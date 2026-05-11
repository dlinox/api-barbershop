<?php

namespace App\Modules\Auth\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Profile;

use App\Modules\Auth\Repositories\SessionRepository;
use App\Modules\Auth\Repositories\UserRepository;
use App\Modules\Auth\Repositories\Queries\MeQuery;

use App\Modules\Auth\Repositories\ProfileRepository;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private SessionRepository $sessionRepository,
        private ProfileRepository $profileRepository,
        private MeQuery $meQuery
    ) {}

    /**
     * Authenticate user by username or email
     */
    public function signIn(string $identifier, string $password, Request $request): ?object
    {
        $user = $this->userRepository->findByIdentifier($identifier);

        if (!$user) throw new ApiException("El usuario no existe", 401);
        if (!$this->userRepository->isActive($user))  throw new ApiException("El usuario no esta activo", 401);
        if (!Hash::check($password, $user->password)) throw new ApiException("Credenciales invalidas", 401);

        // Check active profiles
        $profilesCount = $this->profileRepository->countByUserId($user->id);

        $profileId = null;
        $infId = null;
        $me = null;

        if ($profilesCount === 1) {
            $singleProfile = $this->profileRepository->firstActiveByUserId($user->id);

            if ($singleProfile) {
                $profileId = $singleProfile->id;
                $infId = $this->resolveSingleInfrastructureId($singleProfile);
            }

            $me = ($this->meQuery)($user, $profileId, $infId);
        }

        $token = JWTAuth::claims([
            'prf' => $profileId,
            'inf' => $infId,
        ])->fromUser($user);

        // Create session
        $this->userRepository->updateLastSignIn($user);
        $this->sessionRepository->create(
            $user->id,
            $profileId,
            $request->ip(),
            $request->userAgent()
        );

        return (object)[
            'token' => $token,
            'user' => $me
        ];
    }

    /**
     * Get active profiles for user
     */
    public function getProfiles(): Collection
    {
        $user = JWTAuth::user();
        $profiles = $this->profileRepository->getByUserId($user->id);
        return $profiles;
    }

    /**
     * Select a profile and generate new token
     */
    public function selectProfile(int $profileId): object
    {
        $profile = $this->profileRepository->findById($profileId);

        if (!$profile) throw new ApiException("Perfil no encontrado", 404);

        $user = JWTAuth::user();

        $infId = $this->resolveSingleInfrastructureId($profile);

        $me = ($this->meQuery)($user, $profileId, $infId);

        JWTAuth::invalidate(JWTAuth::getToken());

        $token = JWTAuth::claims([
            'prf' => $profileId,
            'inf' => $infId,
        ])->fromUser($user);

        $this->sessionRepository->updateProfile($user->id, $profileId);

        return (object)[
            'token' => $token,
            'user' => $me
        ];
    }

    /**
     * Get admin infrastructures (sedes) for the authenticated profile
     */
    public function getAdminInfrastructures(): Collection
    {
        $profileId = $this->getProfileIdFromToken();
        if (!$profileId) throw new ApiException("Perfil no encontrado", 401);

        $profile = $this->profileRepository->findById($profileId);
        if (!$profile) throw new ApiException("Perfil no encontrado", 404);

        if ($profile->profileable_type !== 'profile_admins') {
            throw new ApiException("Solo los administradores pueden listar sedes", 403);
        }

        return $this->profileRepository->getAdminInfrastructures($profile->profileable_id);
    }

    /**
     * Select an infrastructure (sede) and generate new token with inf claim
     */
    public function selectInfrastructure(int $infrastructureId): object
    {
        $profileId = $this->getProfileIdFromToken();
        if (!$profileId) throw new ApiException("Perfil no encontrado", 401);

        $profile = $this->profileRepository->findById($profileId);
        if (!$profile) throw new ApiException("Perfil no encontrado", 404);

        if ($profile->profileable_type !== 'profile_admins') {
            throw new ApiException("Acceso no permitido", 403);
        }

        // Validate this infrastructure is assigned to the admin
        $infras = $this->profileRepository->getAdminInfrastructures($profile->profileable_id);
        $validIds = $infras->pluck('id')->toArray();

        if (!in_array($infrastructureId, $validIds)) {
            throw new ApiException("La sede no está asignada a este perfil", 403);
        }

        $user = JWTAuth::user();
        $me = ($this->meQuery)($user, $profileId, $infrastructureId);

        JWTAuth::invalidate(JWTAuth::getToken());

        $token = JWTAuth::claims([
            'prf' => $profileId,
            'inf' => $infrastructureId,
        ])->fromUser($user);

        $this->sessionRepository->updateProfile($user->id, $profileId);

        return (object)[
            'token' => $token,
            'user' => $me
        ];
    }

    /**
     * Refresh the JWT token
     */
    public function refresh(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    /**
     * Sign out (invalidate token and sessions)
     */
    public function signOut(): void
    {
        $user = JWTAuth::user();

        if ($user) {
            $this->sessionRepository->invalidateAllForUser($user->id);
        }

        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * Get authenticated user
     */
    public function me()
    {
        $user = JWTAuth::user();
        $profileId = $this->getProfileIdFromToken();

        // Si el token aún no tiene perfil seleccionado, devolver un usuario
        // mínimo que indique al frontend que debe ir a seleccionar perfil.
        if (!$profileId) {
            return [
                'name'     => $user->username,
                'username' => $user->username,
                'email'    => $user->email,
                'profile'  => [
                    'id'             => null,
                    'role'           => null,
                    'redirectTo'     => '/auth/select-profile',
                    'roleLevel'      => null,
                    'permissions'    => [],
                    'infrastructure' => null,
                ],
            ];
        }

        // infrastructureId = null: MeQuery will read 'inf' from JWT itself
        return ($this->meQuery)($user, $profileId);
    }

    /**
     * Get profile ID from JWT claims
     */
    public function getProfileIdFromToken(): ?int
    {
        $payload = JWTAuth::parseToken()->getPayload();
        return $payload->get('prf');
    }

    /**
     * Authenticate user via Google OAuth (by email)
     */
    public function signInWithGoogle(string $email, Request $request): ?object
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) throw new ApiException("No existe cuenta con este email", 401);
        if (!$this->userRepository->isActive($user)) throw new ApiException("El usuario no esta activo", 401);

        $profilesCount = $this->profileRepository->countByUserId($user->id);

        $profileId = null;
        $infId = null;
        $me = null;

        if ($profilesCount === 1) {
            $singleProfile = $this->profileRepository->firstActiveByUserId($user->id);

            if ($singleProfile) {
                $profileId = $singleProfile->id;
                $infId = $this->resolveSingleInfrastructureId($singleProfile);
            }

            $me = ($this->meQuery)($user, $profileId, $infId);
        }

        $token = JWTAuth::claims([
            'prf' => $profileId,
            'inf' => $infId,
        ])->fromUser($user);

        $this->userRepository->updateLastSignIn($user);
        $this->sessionRepository->create(
            $user->id,
            $profileId,
            $request->ip(),
            $request->userAgent()
        );

        return (object)[
            'token' => $token,
            'user' => $me
        ];
    }

    /**
     * For level 1 admins with exactly 1 infrastructure assigned:
     * returns that infrastructure ID so it can be embedded in the JWT.
     * Returns null for all other profiles or admins with 0 or 2+ infrastructures.
     */
    private function resolveSingleInfrastructureId(Profile $profile): ?int
    {
        $profile->loadMissing('role');

        if ((int) $profile->role->level !== 1) {
            return null;
        }

        if ($profile->profileable_type !== 'profile_admins') {
            return null;
        }

        $infras = $this->profileRepository->getAdminInfrastructures($profile->profileable_id);

        if ($infras->count() === 1) {
            return $infras->first()->id;
        }

        return null;
    }
}
