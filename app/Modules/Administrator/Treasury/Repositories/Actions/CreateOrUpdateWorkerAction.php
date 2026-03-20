<?php

namespace App\Modules\Administrator\Treasury\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;

use App\Modules\Administrator\Treasury\Repositories\WorkerRepository;
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
                $worker = $this->workerRepository->create(
                    $person->id,
                    $data['position'] ?? 'barber',
                    $data['monthly_salary'] ?? null,
                    $data['payment_frequency'] ?? null,
                    $data['is_active'] ?? true,
                );
                if (!$worker) throw new ApiException('Error al crear el perfil de trabajador');
            } else {
                $worker = $this->workerRepository->findByPersonId($data['id']);
                if (!$worker) throw new ApiException('Error al encontrar el perfil de trabajador');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de trabajador no coincide con la persona');
                $this->workerRepository->update(
                    $worker,
                    $data['position'] ?? $worker->position,
                    $data['monthly_salary'] ?? $worker->monthly_salary,
                    $data['payment_frequency'] ?? $worker->payment_frequency,
                    $data['is_active'] ?? $worker->is_active,
                );
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
