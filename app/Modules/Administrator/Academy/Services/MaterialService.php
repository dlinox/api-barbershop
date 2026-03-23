<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\MaterialRepository;
use Illuminate\Http\Request;

class MaterialService
{
    public function __construct(
        private MaterialRepository $materialRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->materialRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->materialRepository->createOrUpdate($data);
    }

    public function getActiveMaterials(?int $branchId = null)
    {
        return $this->materialRepository->getActiveMaterials($branchId);
    }

    public function delete(int $id)
    {
        return $this->materialRepository->delete($id);
    }
}
