<?php

namespace App\Modules\Administrator\Inventory\Services;

use App\Modules\Administrator\Inventory\Repositories\ProductPresentationRepository;
use Illuminate\Http\Request;

class ProductPresentationService
{
    public function __construct(
        private ProductPresentationRepository $productPresentationRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->productPresentationRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->productPresentationRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->productPresentationRepository->delete($id);
    }

    public function getByProduct(int $productId)
    {
        return $this->productPresentationRepository->getByProduct($productId);
    }
}
