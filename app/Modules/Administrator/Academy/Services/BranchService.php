<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\BranchRepository;
use Illuminate\Http\Request;

class BranchService
{
    public function __construct(
        private BranchRepository $branchRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->branchRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->branchRepository->createOrUpdate($data);
    }

    public function getActiveBranches()
    {
        return $this->branchRepository->getActiveBranches();
    }

    public function delete(int $id)
    {
        return $this->branchRepository->delete($id);
    }
}
