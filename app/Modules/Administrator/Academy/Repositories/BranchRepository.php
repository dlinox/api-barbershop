<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Academy\Branch;
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

        //verificar si datos relacionados
        $groups = $branch->groups;
        if ($groups->count() > 0) {
            throw new \Exception('No se puede eliminar la sucursal porque tiene grupos relacionados');
        }

        $rooms = $branch->rooms;
        if ($rooms->count() > 0) {
            throw new \Exception('No se puede eliminar la sucursal porque tiene aulas relacionadas');
        }

        $branch->delete();
        return $branch;
    }

    public function getActiveBranches()
    {
        $query = Branch::with('infrastructure')->where('is_active', true);
        $this->scopeByAcademyBranch($query, 'id');
        return $query->get();
    }
}
