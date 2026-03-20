<?php

namespace App\Modules\Administrator\Academy\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;
use App\Models\Auth\User;
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

        try {
            DB::beginTransaction();

            $person = $this->createOrUpdatePersonAction->execute($data['person'], $data['person']['id']);

            $existingProfile = $this->profileRepository->findByProfileableId($person->id);

            $isActive = $data['user']['is_active'] ?? false;

            if (!$data['id']) {
                // Crear estudiante
                $student = $this->studentRepository->findByPersonId($person->id);
                if ($student) throw new ApiException('La persona ya tiene un perfil de estudiante');

                if ($existingProfile) {
                    // La persona ya tiene un usuario (otro perfil), reutilizar
                    $user = User::find($existingProfile->auth_user_id);
                    if (!$user) throw new ApiException('Error al encontrar el usuario asociado');
                } else {
                    // Crear nuevo usuario
                    $data['user']['password'] = $person->document_number;
                    $user = $this->createOrUpdateUserAction->execute($data['user']);
                }

                $student = $this->studentRepository->create($person->id);
                if (!$student) throw new ApiException('Error al crear el perfil de estudiante');

                $student->update(['is_active' => $isActive]);

                $profileExists = $this->profileRepository->findUserIdAndType($user->id, 'students');
                if ($profileExists) throw new ApiException('El usuario ya tiene un perfil de estudiante');
                $behaviorProfile = $this->profileRepository->create($user->id, 'students', $student->core_person_id, $role->id);
                $behaviorProfile->update(['is_active' => $isActive]);
            } else {
                // Actualizar estudiante
                $student = $this->studentRepository->findByPersonId($data['id']);
                if (!$student) throw new ApiException('Error al encontrar el perfil de estudiante');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de estudiante no coincide con la persona');

                if ($existingProfile && isset($data['user']['id'])) {
                    if ($existingProfile->auth_user_id !== $data['user']['id']) throw new ApiException('El usuario no coincide con la persona');
                }

                $this->createOrUpdateUserAction->execute($data['user']);

                $student->update(['is_active' => $isActive]);

                $studentProfile = $this->profileRepository->findUserIdAndType(
                    $data['user']['id'],
                    'students'
                );
                if ($studentProfile) {
                    $studentProfile->update(['is_active' => $isActive]);
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
