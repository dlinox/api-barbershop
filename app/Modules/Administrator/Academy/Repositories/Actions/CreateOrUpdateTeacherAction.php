<?php

namespace App\Modules\Administrator\Academy\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;
use App\Models\Auth\User;
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

            $isActive = $data['is_active'] ?? true;

            if (!$data['id']) {
                // Crear docente
                $teacher = $this->teacherRepository->findByPersonId($person->id);
                if ($teacher) throw new ApiException('La persona ya tiene un perfil de docente');

                $existingProfile = $this->profileRepository->findByProfileableId($person->id);

                if ($existingProfile) {
                    // La persona ya tiene un usuario (otro perfil), reutilizar
                    $user = User::find($existingProfile->auth_user_id);
                    if (!$user) throw new ApiException('Error al encontrar el usuario asociado');
                } else {
                    // Crear nuevo usuario
                    $user = $this->createOrUpdateUserAction->execute([
                        'username' => $person->document_number,
                        'email' => $person->email,
                        'password' => $person->document_number,
                        'is_active' => $isActive,
                    ]);
                }

                $teacher = $this->teacherRepository->create(
                    $person->id,
                    $data['branch_id'] ?? null,
                    $data['payment_type'] ?? null,
                    $data['monthly_salary'] ?? null,
                    $isActive
                );
                if (!$teacher) throw new ApiException('Error al crear el perfil de docente');

                $profileExists = $this->profileRepository->findUserIdAndType($user->id, 'teachers');
                if ($profileExists) throw new ApiException('El usuario ya tiene un perfil de docente');
                $behaviorProfile = $this->profileRepository->create($user->id, 'teachers', $teacher->core_person_id, $role->id);
                $behaviorProfile->update(['is_active' => $isActive]);
            } else {
                // Actualizar docente (solo datos de persona y docente, no usuario)
                $teacher = $this->teacherRepository->findByPersonId($data['id']);
                if (!$teacher) throw new ApiException('Error al encontrar el perfil de docente');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de docente no coincide con la persona');

                $this->teacherRepository->update(
                    $teacher,
                    $data['branch_id'] ?? null,
                    $data['payment_type'] ?? null,
                    $data['monthly_salary'] ?? null,
                    $isActive
                );

                $existingProfile = $this->profileRepository->findByProfileableIdAndType($person->id, 'teachers');
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
