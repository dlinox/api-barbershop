<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Modules\Administrator\Treasury\Repositories\Actions\SaveExpenseAction;
use App\Modules\Administrator\Treasury\Repositories\GeneralExpenseRepository;

class GeneralExpenseService
{
    public function __construct(
        private readonly GeneralExpenseRepository $repository,
        private readonly SaveExpenseAction $saveAction,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->saveAction->execute($data, 'pending');
    }

    public function getById(int $id)
    {
        return $this->repository->getById($id);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
