<?php

namespace App\Modules\Administrator\Barbershop\Repositories;

use App\Models\Barbershop\Category;

class CategoryRepository
{
    public function dataTable($request)
    {
        return Category::dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        $category = Category::updateOrCreate(['id' => $data['id']], $data);
        return $category;
    }

    public function delete(int $id)
    {
        $category = Category::find($id);

        if ($category->services()->exists()) {
            throw new \Exception('No se puede eliminar la categoría porque tiene servicios relacionados');
        }

        $category->delete();
        return $category;
    }

    public function getActiveCategories()
    {
        return Category::all();
    }
}
