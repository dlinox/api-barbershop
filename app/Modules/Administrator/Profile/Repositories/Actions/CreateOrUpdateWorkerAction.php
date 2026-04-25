<?php

namespace App\Modules\Administrator\Profile\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Role;

use App\Modules\Administrator\Profile\Repositories\WorkerRepository;
use App\Modules\Shared\Repositories\ProfileRepository;
use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;

class CreateOrUpdateWorkerAction
{
    public function __construct(
        private WorkerRepository $workerRepository,
        private ProfileRepository $profileRepository,
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
    ) {}

    public function execute(array $data): void
    {
        $role = Role::where('name', 'trabajador')->where('is_active', true)->first();
        if (!$role) {
            throw new ApiException('El rol trabajador no existe, comuníquese con el administrador');
        }

        try {
            DB::beginTransaction();

            $person = $this->createOrUpdatePersonAction->execute($data['person'], $data['id']);

            $isActive = $data['is_active'] ?? true;

            if (!$data['id']) {
                $worker = $this->workerRepository->findByPersonId($person->id);
                if ($worker) throw new ApiException('La persona ya tiene un perfil de trabajador');

                $worker = $this->workerRepository->create($person->id, [
                    'infrastructure_id' => $data['infrastructure_id'],
                    'position'          => $data['position'] ?? null,
                    'monthly_salary'    => $data['monthly_salary'] ?? null,
                    'payment_frequency' => $data['payment_frequency'] ?? null,
                    'is_active'         => $isActive,
                ]);

                if (!$worker) throw new ApiException('Error al crear el perfil de trabajador');

                $profileExists = $this->profileRepository->findByProfileableIdAndType($worker->id, 'workers');
                if ($profileExists) throw new ApiException('El trabajador ya tiene un perfil asignado');
                $behaviorProfile = $this->profileRepository->create(null, 'profile_workers', $worker->id, $role->id);
                $behaviorProfile->update(['is_active' => $isActive]);
            } else {
                $worker = $this->workerRepository->findByPersonId($data['id']);
                if (!$worker) throw new ApiException('Error al encontrar el perfil de trabajador');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de trabajador no coincide con la persona');

                $this->workerRepository->update($data['id'], [
                    'infrastructure_id' => $data['infrastructure_id'],
                    'position'          => $data['position'] ?? null,
                    'monthly_salary'    => $data['monthly_salary'] ?? null,
                    'payment_frequency' => $data['payment_frequency'] ?? null,
                    'is_active'         => $isActive,
                ]);

                $existingProfile = $this->profileRepository->findByProfileableIdAndType($person->id, 'workers');
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
