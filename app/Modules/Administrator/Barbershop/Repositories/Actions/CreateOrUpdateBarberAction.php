<?php

namespace App\Modules\Administrator\Barbershop\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;
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

            $profile = $this->profileRepository->findByProfileableId($person->id);

            if ($profile !== null && isset($data['user']['id'])) {
                if ($profile->auth_user_id !== $data['user']['id']) throw new ApiException('El usuario no coincide con la persona');
            }

            $data['user']['password'] = $person->document_number;
            $data['user']['is_active'] = $data['user']['is_active'] ?? true;

            $user = $this->createOrUpdateUserAction->execute($data['user']);

            if (!$data['id']) {
                $barber = $this->barberRepository->findByPersonId($person->id);
                if ($barber) throw new ApiException('La persona ya tiene un perfil de barbero');

                $barber = $this->barberRepository->create($person->id, [
                    'branch_id' => $data['branch_id'],
                    'commission_percentage' => $data['commission_percentage'],
                    // 'is_active' => $data['is_active'],
                ]);

                if (!$barber) throw new ApiException('Error al crear el perfil de barbero');

                $profile = $this->profileRepository->findUserIdAndType($user->id, 'profile_barbers');
                if ($profile) throw new ApiException('El usuario ya tiene un perfil de barbero');
                $profile = $this->profileRepository->create($user->id, 'profile_barbers', $barber->id, $role->id);
            } else {

                $barber = $this->barberRepository->findByPersonId($data['id']);
                if (!$barber) throw new ApiException('Error al encontrar el perfil de barbero');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de barbero no coincide con la persona');

                $this->barberRepository->update($data['id'], [
                    'branch_id' => $data['branch_id'],
                    'commission_percentage' => $data['commission_percentage'],
                    // 'is_active' => $data['is_active'],
                ]);
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
