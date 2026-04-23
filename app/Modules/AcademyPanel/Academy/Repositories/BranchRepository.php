<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Academy\Branch;
use App\Common\Http\Context\AdminContext;

class BranchRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::academyBranchId();
        $query = Branch::where('id', $branchId);
        if (empty($request->sortBy)) { $query->orderBy('id', 'desc'); }
        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return Branch::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $branch = Branch::findOrFail($id);
        if ($branch->groups->count() > 0) throw new \Exception('No se puede eliminar la sucursal porque tiene grupos relacionados');
        if ($branch->rooms->count() > 0) throw new \Exception('No se puede eliminar la sucursal porque tiene aulas relacionadas');
        $branch->delete();
        return $branch;
    }

    public function getActiveBranches()
    {
        $branchId = AdminContext::academyBranchId();
        return Branch::with('infrastructure')->where('is_active', true)->where('id', $branchId)->get();
    }
}