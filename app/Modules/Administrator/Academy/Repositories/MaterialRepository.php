<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Common\Traits\HasInfrastructureScope;
use App\Models\Academy\Branch;
use App\Models\Academy\Material;

class MaterialRepository
{
    use HasInfrastructureScope;

    public function dataTable($request)
    {
        $query = Material::select(
            'academy_materials.id',
            'academy_materials.branch_id',
            'academy_materials.quantity',

            'academy_branches.name as branch_name',

            'inventory_products.id as product_id',
            'inventory_products.name as product_name',

            'inventory_product_presentations.id as product_presentation_id',
            'inventory_product_presentations.name as product_presentation_name',
            'inventory_product_presentations.unit_type as product_presentation_unit_type',
            'inventory_product_presentations.quantity as product_presentation_quantity',

            'academy_materials.is_active',
        )
            ->join('academy_branches', 'academy_materials.branch_id', '=', 'academy_branches.id')
            ->join('inventory_product_presentations', 'academy_materials.presentation_id', '=', 'inventory_product_presentations.id')
            ->join('inventory_products', 'inventory_product_presentations.product_id', '=', 'inventory_products.id');

        $this->scopeByAcademyBranch($query, 'academy_materials.branch_id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('academy_materials.id', 'desc');
        }

        $items = $query->dataTable($request);
        return $items;
    }

    public function createOrUpdate(array $data)
    {
        $branch = Branch::findOrFail($data['branch_id']);
        $infrastructureId = $branch->getInfrastructureId();
        if ($infrastructureId) {
            $this->validateInfrastructureAccess($infrastructureId);
        }

        $material = Material::updateOrCreate(['id' => $data['id']], $data);
        return $material;
    }

    public function delete(int $id)
    {
        $material = Material::findOrFail($id);

        $branch = $material->branch;
        if ($branch) {
            $infrastructureId = $branch->getInfrastructureId();
            if ($infrastructureId) {
                $this->validateInfrastructureAccess($infrastructureId);
            }
        }

        if ($material->enrollments()->exists()) {
            throw new \Exception('No se puede eliminar el material porque tiene inscripciones asociadas');
        }

        return $material->delete();
    }

    public function getActiveMaterials(?int $branchId = null)
    {
        $query = Material::select(
            'academy_materials.id',
            'academy_materials.quantity',
            'academy_materials.presentation_id',
            'academy_materials.branch_id',

            'inventory_product_presentations.name as presentation_name',
            'inventory_products.name as product_name',
        )
            ->join('inventory_product_presentations', 'academy_materials.presentation_id', '=', 'inventory_product_presentations.id')
            ->join('inventory_products', 'inventory_product_presentations.product_id', '=', 'inventory_products.id')
            ->where('academy_materials.is_active', true);

        if ($branchId) {
            $query->where('academy_materials.branch_id', $branchId);
        } else {
            $this->scopeByAcademyBranch($query, 'academy_materials.branch_id');
        }

        return $query->get();
    }
}
