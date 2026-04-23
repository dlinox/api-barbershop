<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Academy\Material;
use App\Common\Http\Context\AdminContext;

class MaterialRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::academyBranchId();
        $query = Material::query()->where('academy_materials.branch_id', $branchId);
        if (empty($request->sortBy)) { $query->orderBy('id', 'desc'); }
        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        $data['branch_id'] = AdminContext::academyBranchId();
        return Material::updateOrCreate(['id' => $data['id'] ?? null], $data);
    }

    public function delete(int $id)
    {
        $material = Material::findOrFail($id);
        $material->delete();
        return $material;
    }

    public function getActiveMaterials() { return Material::where('is_active', true)->get(); }
}