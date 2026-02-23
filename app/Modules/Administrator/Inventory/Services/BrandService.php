<?php

namespace App\Modules\Administrator\Inventory\Services;

use App\Modules\Administrator\Inventory\Repositories\BrandRepository;
use Illuminate\Http\Request;

class BrandService
{
    public function __construct(
        private BrandRepository $brandRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->brandRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->brandRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->brandRepository->delete($id);
    }

    public function getActiveBrands()
    {
        return $this->brandRepository->getActiveBrands();
    }
}
