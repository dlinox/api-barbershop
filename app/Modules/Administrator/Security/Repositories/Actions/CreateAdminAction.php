<?php

namespace App\Modules\Administrator\Security\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Role;

use App\Modules\Administrator\Security\Repositories\AdminRepository;
use App\Modules\Shared\Repositories\ProfileRepository;

use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;

class CreateAdminAction
{
    public function __construct(
        private AdminRepository $adminRepository,
        private ProfileRepository $profileRepository,
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
    ) {}

    public function execute(array $data): void
    {
        $roleExist = Role::where('id', $data['roleId'])->where('is_active', true)->where('level', '1')->exists();

        if (!$roleExist) throw new ApiException('El rol seleccionado no es válido.');

        DB::beginTransaction();
        try {
            $person = $this->createOrUpdatePersonAction->execute($data['person']);
            $admin = $this->adminRepository->findByPersonId($person->id);

            if ($admin) throw new ApiException('La persona ya tiene un perfil de administrador');

            $user = $this->createOrUpdateUserAction->execute($data['user']);
            $profile = $this->profileRepository->findUserIdAndType($user->id, 'admins');

            if ($profile) throw new ApiException('El usuario ya tiene un perfil de administrador');

            $admin = $this->adminRepository->create($person->id);
            $profile = $this->profileRepository->create($user->id, 'admins', $admin->id, $data['roleId']);

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
