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

            $isActive = $data['is_active'] ?? true;

            if (!$data['id']) {
                // Crear barbero
                $barber = $this->barberRepository->findByPersonId($person->id);
                if ($barber) throw new ApiException('La persona ya tiene un perfil de barbero');

                $existingProfile = $this->profileRepository->findByProfileableId($person->id);

                if ($existingProfile) {
                    // La persona ya tiene un usuario (otro perfil), reutilizar
                    $user = User::find($existingProfile->auth_user_id);
                    if (!$user) throw new ApiException('Error al encontrar el usuario asociado');
                } else {
                    // Crear nuevo usuario automáticamente
                    $user = $this->createOrUpdateUserAction->execute([
                        'username' => $person->document_number,
                        'email' => $person->email,
                        'password' => $person->document_number,
                        'is_active' => $isActive,
                    ]);
                }

                $barber = $this->barberRepository->create($person->id, [
                    'branch_id' => $data['branch_id'],
                    'commission_percentage' => $data['commission_percentage'],
                    'is_active' => $isActive,
                ]);

                if (!$barber) throw new ApiException('Error al crear el perfil de barbero');

                $profileExists = $this->profileRepository->findUserIdAndType($user->id, 'barbers');
                if ($profileExists) throw new ApiException('El usuario ya tiene un perfil de barbero');
                $behaviorProfile = $this->profileRepository->create($user->id, 'barbers', $barber->id, $role->id);
                $behaviorProfile->update(['is_active' => $isActive]);
            } else {
                // Actualizar barbero (solo datos de persona y barbero, no usuario)
                $barber = $this->barberRepository->findByPersonId($data['id']);
                if (!$barber) throw new ApiException('Error al encontrar el perfil de barbero');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de barbero no coincide con la persona');

                $this->barberRepository->update($data['id'], [
                    'branch_id' => $data['branch_id'],
                    'commission_percentage' => $data['commission_percentage'],
                    'is_active' => $isActive,
                ]);

                $existingProfile = $this->profileRepository->findByProfileableIdAndType($person->id, 'barbers');
                if ($existingProfile) {
                    $existingProfile->update(['is_active' => $isActive]);
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
