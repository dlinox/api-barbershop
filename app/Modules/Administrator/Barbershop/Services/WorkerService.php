<?php

namespace App\Modules\Administrator\Barbershop\Services;

use App\Modules\Administrator\Barbershop\Repositories\WorkerRepository;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CreateOrUpdateWorkerAction;
use App\Modules\Administrator\Barbershop\Repositories\Actions\DeleteWorkerAction;

class WorkerService
{
    public function __construct(
        private WorkerRepository $workerRepository,
        private CreateOrUpdateWorkerAction $createOrUpdateWorkerAction,
        private DeleteWorkerAction $deleteWorkerAction,
    ) {}

    public function dataTable($request)
    {
        return $this->workerRepository->dataTable($request);
    }

    public function save($data)
    {
        return $this->createOrUpdateWorkerAction->execute($data);
    }

    public function delete(int $id)
    {
        return $this->deleteWorkerAction->execute($id);
    }

    public function selectAsyncItems($request)
    {
        return $this->workerRepository->selectAsyncItems($request->search);
    }
}
