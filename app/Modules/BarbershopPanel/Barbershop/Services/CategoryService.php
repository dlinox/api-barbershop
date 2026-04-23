<?php

namespace App\Modules\BarbershopPanel\Barbershop\Services;

use App\Modules\BarbershopPanel\Barbershop\Repositories\CategoryRepository;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    public function dataTable($request)
    {
        return $this->categoryRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->categoryRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->categoryRepository->delete($id);
    }

    public function getActiveCategories()
    {
        return $this->categoryRepository->getActiveCategories();
    }
}