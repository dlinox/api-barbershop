<?php

namespace App\Modules\Administrator\Barbershop\Repositories;

use App\Models\Barbershop\Branch;
use App\Common\Traits\HasInfrastructureScope;

class BranchRepository
{
    use HasInfrastructureScope;

    public function dataTable($request)
    {
        $query = Branch::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        $branch = Branch::updateOrCreate(['id' => $data['id']], $data);
        return $branch;
    }

    public function delete(int $id)
    {
        $branch = Branch::find($id);

        if ($branch->services()->exists()) {
            throw new \Exception('No se puede eliminar la sucursal porque tiene servicios relacionados');
        }

        $branch->delete();
        return $branch;
    }

    public function getActiveBranches()
    {
        $query = Branch::where('is_active', true);
        $this->scopeByBranch($query, 'id');
        return $query->get();
    }
}
