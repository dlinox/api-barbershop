<?php

namespace App\Modules\Administrator\Barbershop\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;

use App\Modules\Administrator\Barbershop\Repositories\WorkerRepository;
use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;

class CreateOrUpdateWorkerAction
{
    public function __construct(
        private WorkerRepository $workerRepository,
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
    ) {}

    public function execute(array $data): void
    {
        try {
            DB::beginTransaction();

            $person = $this->createOrUpdatePersonAction->execute($data['person'], $data['id']);

            if (!$data['id']) {
                $worker = $this->workerRepository->findByPersonId($person->id);
                if ($worker) throw new ApiException('La persona ya tiene un perfil de trabajador');
                $worker = $this->workerRepository->create($person->id, $data['position'] ?? 'barber');
                if (!$worker) throw new ApiException('Error al crear el perfil de trabajador');
            } else {
                $worker = $this->workerRepository->findByPersonId($data['id']);
                if (!$worker) throw new ApiException('Error al encontrar el perfil de trabajador');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de trabajador no coincide con la persona');
                $worker->update(['position' => $data['position'] ?? $worker->position]);
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
