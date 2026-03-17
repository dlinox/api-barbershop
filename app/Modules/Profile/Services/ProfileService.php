<?php

namespace App\Modules\Profile\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

use App\Common\Exceptions\ApiException;
use App\Models\Auth\User;
use App\Models\Core\Person;

use App\Modules\Profile\Repositories\ProfileRepository;

class ProfileService
{
    public function __construct(
        private ProfileRepository $profileRepository,
    ) {}

    public function getProfile(): array
    {
        $user = JWTAuth::user();
        $person = $this->profileRepository->getPersonByUserId($user->id);

        if (!$person) throw new ApiException('Persona no encontrada', 404);

        $profiles = $this->profileRepository->getAllProfilesByUserId($user->id);

        return [
            'person' => [
                'name' => $person->name,
                'paternalSurname' => $person->paternal_surname,
                'maternalSurname' => $person->maternal_surname,
                'documentType' => $person->document_type,
                'documentNumber' => $person->document_number,
                'phone' => $person->phone,
            ],
            'account' => [
                'username' => $user->username,
                'email' => $user->email,
            ],
            'profiles' => $profiles->map(function ($profile) {
                return [
                    'id' => $profile->id,
                    'role' => $profile->role_name,
                    'type' => $profile->profileable_type,
                    'isActive' => $profile->is_active,
                ];
            })->values()->toArray(),
        ];
    }

    public function updatePersonalData(array $data): void
    {
        $user = JWTAuth::user();
        $person = $this->profileRepository->getPersonByUserId($user->id);

        if (!$person) throw new ApiException('Persona no encontrada', 404);

        $this->validatePersonData($data, $person->id);

        DB::beginTransaction();
        try {
            $person->update($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), 500);
        }
    }

    public function updateAccountData(array $data): void
    {
        $user = JWTAuth::user();

        $this->validateAccountData($data, $user->id);

        DB::beginTransaction();
        try {
            $user->update($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), 500);
        }
    }

    public function changePassword(array $data): void
    {
        $user = JWTAuth::user();

        if (!Hash::check($data['current_password'], $user->password)) {
            throw new ApiException('La contraseña actual es incorrecta');
        }

        if ($data['new_password'] !== $data['new_password_confirmation']) {
            throw new ApiException('Las contraseñas no coinciden');
        }

        DB::beginTransaction();
        try {
            $user->update(['password' => $data['new_password']]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), 500);
        }
    }

    private function validatePersonData(array $data, int $personId): void
    {
        if (!empty($data['document_number'])) {
            $exists = Person::where('document_type', $data['document_type'])
                ->where('document_number', $data['document_number'])
                ->where('id', '!=', $personId)
                ->exists();
            if ($exists) throw new ApiException('El documento ya fue registrado por otra persona');
        }

        if (!empty($data['phone'])) {
            $exists = Person::where('phone', $data['phone'])
                ->where('id', '!=', $personId)
                ->exists();
            if ($exists) throw new ApiException('El número de celular ya fue registrado por otra persona');
        }
    }

    private function validateAccountData(array $data, int $userId): void
    {
        if (!empty($data['username'])) {
            $exists = User::where('username', $data['username'])
                ->where('id', '!=', $userId)
                ->exists();
            if ($exists) throw new ApiException('El nombre de usuario ya existe');
        }

        if (!empty($data['email'])) {
            $exists = User::where('email', $data['email'])
                ->where('id', '!=', $userId)
                ->exists();
            if ($exists) throw new ApiException('El correo electrónico ya existe');
        }
    }
}
