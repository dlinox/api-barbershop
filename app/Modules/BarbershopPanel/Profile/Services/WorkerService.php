<?php

namespace App\Modules\BarbershopPanel\Profile\Services;

use App\Modules\BarbershopPanel\Profile\Repositories\WorkerRepository;
use App\Modules\BarbershopPanel\Profile\Repositories\Actions\CreateOrUpdateWorkerAction;

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

    public function selectAsyncItems($request)
    {
        return $this->workerRepository->selectAsyncItems($request->search ?? null);
    }
}
