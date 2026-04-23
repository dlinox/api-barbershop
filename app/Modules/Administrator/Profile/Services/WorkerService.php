<?php

namespace App\Modules\Administrator\Profile\Services;

use App\Modules\Administrator\Profile\Repositories\WorkerRepository;
use App\Modules\Administrator\Profile\Repositories\Actions\CreateOrUpdateWorkerAction;

class WorkerService
{
    public function __construct(
        private WorkerRepository $workerRepository,
        private CreateOrUpdateWorkerAction $createOrUpdateWorkerAction,
    ) {}

    public function dataTable($request)
    {
        return $this->workerRepository->dataTable($request);
    }

    public function save(array $data): void
    {
        $this->createOrUpdateWorkerAction->execute($data);
    }

    public function delete(int $id): void
    {
        $this->workerRepository->delete($id);
    }
}
