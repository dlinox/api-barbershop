<?php

namespace App\Modules\Administrator\Security\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Role;

use App\Modules\Administrator\Security\Repositories\AdminRepository;
use App\Modules\Shared\Repositories\ProfileRepository;

use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;

class CreateOrUpdateAdminAction
{
    public function __construct(
        private AdminRepository $adminRepository,
        private ProfileRepository $profileRepository,
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
    ) {}

    public function execute(array $data): void
    {

        $role = Role::where('id', $data['role_id'])->where('is_active', true)->where('level', '1')->first();
        if (!$role) throw new ApiException('El seleccionado no es un rol válido');

        DB::beginTransaction();
        try {
            $person = $this->createOrUpdatePersonAction->execute($data['person'], $data['id']);

            $profile = $this->profileRepository->findByProfileableId($person->id);

            if ($profile !== null && isset($data['user']['id'])) {
                if ($profile->auth_user_id !== $data['user']['id']) throw new ApiException('El usuario no coincide con la persona');
            }

            $data['user']['password'] = $person->document_number;

            $user = $this->createOrUpdateUserAction->execute($data['user']);

            if (!$data['id']) {
                $admin = $this->adminRepository->findByPersonId($person->id);
                if ($admin) throw new ApiException('La persona ya tiene un perfil de administrador');
                $admin = $this->adminRepository->create($person->id);
                if (!$admin) throw new ApiException('Error al crear el perfil de administrador');

                $profile = $this->profileRepository->findUserIdAndType($user->id, 'admins');
                if ($profile) throw new ApiException('El usuario ya tiene un perfil de administrador');
                $profile = $this->profileRepository->create($user->id, 'admins', $admin->core_person_id, $role->id);
            } else {

                $admin = $this->adminRepository->findByPersonId($data['id']);
                if (!$admin) throw new ApiException('Error al encontrar el perfil de administrador');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de administrador no coincide con la persona');
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
