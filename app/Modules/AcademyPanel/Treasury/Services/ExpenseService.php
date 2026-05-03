<?php

namespace App\Modules\AcademyPanel\Treasury\Services;

use App\Modules\AcademyPanel\Treasury\Repositories\ExpenseRepository;
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

    public function approve(int $id)
    {
        return $this->repository->approve($id);
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
