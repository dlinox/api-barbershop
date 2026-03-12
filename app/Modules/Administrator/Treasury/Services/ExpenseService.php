<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Modules\Administrator\Treasury\Repositories\ExpenseRepository;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepository $repository,
    ) {}

    public function listByCashSession(int $cashSessionId)
    {
        return $this->repository->listByCashSession($cashSessionId);
    }

    public function save(array $data)
    {
        return $this->repository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
