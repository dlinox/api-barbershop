<?php

namespace App\Modules\Administrator\Barbershop\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;
use App\Models\Auth\User;
use App\Models\Behavior\Role;

use App\Modules\Administrator\Barbershop\Repositories\BarberRepository;
use App\Modules\Shared\Repositories\ProfileRepository;

use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;

class CreateOrUpdateBarberAction
{
    public function __construct(
        private BarberRepository $barberRepository,
        private ProfileRepository $profileRepository,
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
    ) {}

    public function execute(array $data): void
    {
        $role = Role::where('name', 'barbero')->where('is_active', true)->where('level', '4')->first();
        if (!$role) {
            throw new ApiException('El rol barber no existe, comuníquese con el administrador');
        }

        try {
            DB::beginTransaction();

            $person = $this->createOrUpdatePersonAction->execute($data['person'], $data['id']);

            $existingProfile = $this->profileRepository->findByProfileableId($person->id);

            $isActive = $data['is_active'] ?? $data['user']['is_active'] ?? false;
            $data['user']['is_active'] = $isActive;

            if (!$data['id']) {
                // Crear barbero
                $barber = $this->barberRepository->findByPersonId($person->id);
                if ($barber) throw new ApiException('La persona ya tiene un perfil de barbero');

                if ($existingProfile) {
                    // La persona ya tiene un usuario (otro perfil), reutilizar
                    $user = User::find($existingProfile->auth_user_id);
                    if (!$user) throw new ApiException('Error al encontrar el usuario asociado');
                } else {
                    // Crear nuevo usuario
                    $data['user']['password'] = $person->document_number;
                    $user = $this->createOrUpdateUserAction->execute($data['user']);
                }

                $barber = $this->barberRepository->create($person->id, [
                    'branch_id' => $data['branch_id'],
                    'commission_percentage' => $data['commission_percentage'],
                    'is_active' => $isActive,
                ]);

                if (!$barber) throw new ApiException('Error al crear el perfil de barbero');

                $profileExists = $this->profileRepository->findUserIdAndType($user->id, 'profile_barbers');
                if ($profileExists) throw new ApiException('El usuario ya tiene un perfil de barbero');
                $behaviorProfile = $this->profileRepository->create($user->id, 'profile_barbers', $barber->id, $role->id);
                $behaviorProfile->update(['is_active' => $isActive]);
            } else {
                // Actualizar barbero
                $barber = $this->barberRepository->findByPersonId($data['id']);
                if (!$barber) throw new ApiException('Error al encontrar el perfil de barbero');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de barbero no coincide con la persona');

                if ($existingProfile && isset($data['user']['id'])) {
                    if ($existingProfile->auth_user_id !== $data['user']['id']) throw new ApiException('El usuario no coincide con la persona');
                }

                $this->createOrUpdateUserAction->execute($data['user']);

                $this->barberRepository->update($data['id'], [
                    'branch_id' => $data['branch_id'],
                    'commission_percentage' => $data['commission_percentage'],
                    'is_active' => $isActive,
                ]);

                $barberProfile = $this->profileRepository->findUserIdAndType(
                    $data['user']['id'],
                    'profile_barbers'
                );
                if ($barberProfile) {
                    $barberProfile->update(['is_active' => $isActive]);
                }
            }

            DB::commit();
        } catch (ApiException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage());
        }
    }
}
