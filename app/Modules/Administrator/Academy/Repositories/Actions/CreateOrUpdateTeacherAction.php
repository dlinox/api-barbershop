<?php

namespace App\Modules\Administrator\Academy\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Role;

use App\Modules\Administrator\Academy\Repositories\TeacherRepository;
use App\Modules\Shared\Repositories\ProfileRepository;

use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;

class CreateOrUpdateTeacherAction
{
    public function __construct(
        private TeacherRepository $teacherRepository,
        private ProfileRepository $profileRepository,
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
    ) {}

    public function execute(array $data): void
    {

        $role = Role::where('name', 'docentes')->where('is_active', true)->where('level', '2')->first();
        if (!$role) throw new ApiException('El rol docente no existe, comuníquese con el administrador');

        try {
            DB::beginTransaction();

            $person = $this->createOrUpdatePersonAction->execute($data['person'], $data['id']);

            $profile = $this->profileRepository->findByProfileableId($person->id);

            if ($profile !== null && isset($data['user']['id'])) {
                if ($profile->auth_user_id !== $data['user']['id']) throw new ApiException('El usuario no coincide con la persona');
            }

            $data['user']['password'] = $person->document_number;
            $data['user']['is_active'] = false; // TODO: Activar cuando se implemente el correo electrónico

            $user = $this->createOrUpdateUserAction->execute($data['user']);

            if (!$data['id']) {
                $teacher = $this->teacherRepository->findByPersonId($person->id);
                if ($teacher) throw new ApiException('La persona ya tiene un perfil de docente');
                $teacher = $this->teacherRepository->create($person->id);
                if (!$teacher) throw new ApiException('Error al crear el perfil de docente');

                $profile = $this->profileRepository->findUserIdAndType($user->id, 'teachers');
                if ($profile) throw new ApiException('El usuario ya tiene un perfil de docente');
                $profile = $this->profileRepository->create($user->id, 'teachers', $teacher->core_person_id, $role->id);
            } else {

                $teacher = $this->teacherRepository->findByPersonId($data['id']);
                if (!$teacher) throw new ApiException('Error al encontrar el perfil de docente');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de docente no coincide con la persona');
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
