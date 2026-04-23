<?php

namespace App\Modules\BarbershopPanel\Barbershop\Services;

use App\Modules\BarbershopPanel\Barbershop\Repositories\BranchRepository;

class BranchService
{
    public function __construct(
        private BranchRepository $branchRepository
    ) {}

    public function dataTable($request)
    {
        return $this->branchRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->branchRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->branchRepository->delete($id);
    }

    public function getActiveBranches()
    {
        return $this->branchRepository->getActiveBranches();
    }
}