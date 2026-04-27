<?php

namespace App\Modules\AcademyPanel\Profile\Repositories\Actions;

use App\Common\Http\Context\AdminContext;
use App\Common\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;

use App\Modules\AcademyPanel\Profile\Repositories\WorkerRepository;
use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;

class CreateOrUpdateWorkerAction
{
    public function __construct(
        private WorkerRepository $workerRepository,
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
    ) {}

    public function execute(array $data): void
    {
        $infrastructureId = AdminContext::infrastructureId();

        try {
            DB::beginTransaction();

            $person = $this->createOrUpdatePersonAction->execute($data['person'], $data['id']);

            $isActive = $data['is_active'] ?? true;

            if (!$data['id']) {
                $worker = $this->workerRepository->findByPersonId($person->id);
                if ($worker) throw new ApiException('La persona ya tiene un perfil de trabajador');

                $worker = $this->workerRepository->create($person->id, [
                    'infrastructure_id' => $infrastructureId,
                    'position'          => $data['position'] ?? null,
                    'monthly_salary'    => $data['monthly_salary'] ?? null,
                    'payment_frequency' => $data['payment_frequency'] ?? null,
                    'is_active'         => $isActive,
                ]);

                if (!$worker) throw new ApiException('Error al crear el perfil de trabajador');
            } else {
                $worker = $this->workerRepository->findByPersonId($data['id']);
                if (!$worker) throw new ApiException('Error al encontrar el perfil de trabajador');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de trabajador no coincide con la persona');

                $this->workerRepository->update($data['id'], [
                    'infrastructure_id' => $infrastructureId,
                    'position'          => $data['position'] ?? null,
                    'monthly_salary'    => $data['monthly_salary'] ?? null,
                    'payment_frequency' => $data['payment_frequency'] ?? null,
                    'is_active'         => $isActive,
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
