<?php

namespace App\Modules\BarbershopPanel\Treasury\Services;

use App\Modules\BarbershopPanel\Treasury\Repositories\ExpenseRepository;
use Illuminate\Http\Request;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepository $repository,
    ) {}

    public function dataTable(Request $request)
    {
        return $this->repository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->repository->save($data);
    }

    public function cancel(int $id)
    {
        return $this->repository->cancel($id);
    }

    public function getById(int $id)
    {
        return $this->repository->getById($id);
    }
}
