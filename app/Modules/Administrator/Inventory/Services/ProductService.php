<?php

namespace App\Modules\Administrator\Inventory\Services;

use App\Modules\Administrator\Inventory\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->productRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->productRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->productRepository->delete($id);
    }

    public function getActiveProducts(?int $infrastructureId = null)
    {
        return $this->productRepository->getActiveProducts($infrastructureId);
    }
}
