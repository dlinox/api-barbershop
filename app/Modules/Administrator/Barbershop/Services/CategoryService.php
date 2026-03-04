<?php

namespace App\Modules\Administrator\Barbershop\Services;

use App\Modules\Administrator\Barbershop\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    public function dataTable(Request $request)
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
