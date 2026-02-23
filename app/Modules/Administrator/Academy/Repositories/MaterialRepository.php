<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Academy\Material;

class MaterialRepository
{
    public function dataTable($request)
    {
        $query = Material::select(
            'academy_materials.id',
            'academy_materials.quantity',
            'academy_materials.product_id',
            'inventory_products.name as product_name',
            'academy_materials.is_active',
        )
            ->join('inventory_products', 'academy_materials.product_id', '=', 'inventory_products.id');

        $items = $query->dataTable($request);
        return $items;
    }

    public function createOrUpdate(array $data)
    {
        $material = Material::updateOrCreate(['id' => $data['id']], $data);
        return $material;
    }

    public function delete(int $id)
    {
        $material = Material::find($id);

        if ($material->enrollments()->exists()) {
            throw new \Exception('No se puede eliminar el material porque tiene inscripciones asociadas');
        }

        return $material->delete();
    }

    public function getActiveMaterials()
    {
        return Material::select(
            'academy_materials.id',
            'academy_materials.quantity',
            'academy_materials.product_id',
            'inventory_products.name as product_name',
        )
            ->join('inventory_products', 'academy_materials.product_id', '=', 'inventory_products.id')
            ->where('is_active', true)->get();
    }
}
