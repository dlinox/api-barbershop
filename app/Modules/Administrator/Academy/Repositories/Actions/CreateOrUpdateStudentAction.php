<?php

namespace App\Modules\Administrator\Academy\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Role;

use App\Modules\Administrator\Academy\Repositories\StudentRepository;
use App\Modules\Shared\Repositories\ProfileRepository;

use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;

class CreateOrUpdateStudentAction
{
    public function __construct(
        private StudentRepository $studentRepository,
        private ProfileRepository $profileRepository,
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
    ) {}

    public function execute(array $data): void
    {

        $role = Role::where('name', 'estudiante')->where('is_active', true)->where('level', '3')->first();
        if (!$role) throw new ApiException('El rol estudiante no existe, comuníquese con el administrador');

        DB::beginTransaction();
        try {
            $person = $this->createOrUpdatePersonAction->execute($data['person'], $data['id']);

            $profile = $this->profileRepository->findByProfileableId($person->id);

            if ($profile !== null && isset($data['user']['id'])) {
                if ($profile->auth_user_id !== $data['user']['id']) throw new ApiException('El usuario no coincide con la persona');
            }

            $data['user']['password'] = $person->document_number;
            $data['user']['is_active'] = false; // TODO: Activar cuando se implemente el correo electrónico

            $user = $this->createOrUpdateUserAction->execute($data['user']);

            if (!$data['id']) {
                $student = $this->studentRepository->findByPersonId($person->id);
                if ($student) throw new ApiException('La persona ya tiene un perfil de estudiante');
                $student = $this->studentRepository->create($person->id);
                if (!$student) throw new ApiException('Error al crear el perfil de estudiante');

                $profile = $this->profileRepository->findUserIdAndType($user->id, 'students');
                if ($profile) throw new ApiException('El usuario ya tiene un perfil de estudiante');
                $profile = $this->profileRepository->create($user->id, 'students', $student->core_person_id, $role->id);
            } else {

                $student = $this->studentRepository->findByPersonId($data['id']);
                if (!$student) throw new ApiException('Error al encontrar el perfil de estudiante');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de estudiante no coincide con la persona');
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
