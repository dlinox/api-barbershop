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

            'inventory_products.id as product_id',
            'inventory_products.name as product_name',

            'inventory_product_presentations.id as product_presentation_id',
            'inventory_product_presentations.name as product_presentation_name',
            'inventory_product_presentations.unit_type as product_presentation_unit_type',
            'inventory_product_presentations.quantity as product_presentation_quantity',

            'academy_materials.is_active',
        )
            ->join('inventory_product_presentations', 'academy_materials.presentation_id', '=', 'inventory_product_presentations.id')
            ->join('inventory_products', 'inventory_product_presentations.product_id', '=', 'inventory_products.id');

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
            'academy_materials.presentation_id',

            'inventory_product_presentations.name as presentation_name',
            'inventory_products.name as product_name',
        )
            ->join('inventory_product_presentations', 'academy_materials.presentation_id', '=', 'inventory_product_presentations.id')
            ->join('inventory_products', 'inventory_product_presentations.product_id', '=', 'inventory_products.id')
            ->where('academy_materials.is_active', true)->get();
    }
}
