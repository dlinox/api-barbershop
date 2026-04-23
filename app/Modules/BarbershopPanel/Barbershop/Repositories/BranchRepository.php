<?php

namespace App\Modules\BarbershopPanel\Barbershop\Repositories;

use App\Models\Barbershop\Branch;
use App\Common\Http\Context\AdminContext;

class BranchRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::barbershopBranchId();

        $query = Branch::where('id', $branchId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return Branch::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $branch = Branch::findOrFail($id);

        if ($branch->services()->exists()) {
            throw new \Exception('No se puede eliminar la sucursal porque tiene servicios relacionados');
        }

        $branch->delete();
        return $branch;
    }

    public function getActiveBranches()
    {
        $branchId = AdminContext::barbershopBranchId();
        return Branch::where('is_active', true)->where('id', $branchId)->get();
    }
}