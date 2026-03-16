<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\Category;

class CategoryRepository
{
    public function dataTable($request)
    {
        return Category::dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return Category::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $category = Category::findOrFail($id);

        if ($category->children()->count() > 0) {
            throw new \Exception('No se puede eliminar la categoría porque tiene subcategorías relacionadas');
        }

        if ($category->products()->count() > 0) {
            throw new \Exception('No se puede eliminar la categoría porque tiene productos relacionados');
        }

        $category->delete();
        return $category;
    }

    public function getActiveCategories(?string $type = null)
    {
        $query = Category::where('is_active', true);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->get();
    }
}
