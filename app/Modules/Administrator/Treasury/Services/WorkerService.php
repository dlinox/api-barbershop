<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Modules\Administrator\Treasury\Repositories\WorkerRepository;
use App\Modules\Administrator\Treasury\Repositories\Actions\CreateOrUpdateWorkerAction;
use App\Modules\Administrator\Treasury\Repositories\Actions\DeleteWorkerAction;

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

    public function paymentSummaryDataTable($request)
    {
        return $this->workerRepository->paymentSummaryDataTable($request);
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

    public function paymentCalculation(int $workerId, string $periodStart, string $periodEnd): array
    {
        return $this->workerRepository->paymentCalculation($workerId, $periodStart, $periodEnd);
    }
}
