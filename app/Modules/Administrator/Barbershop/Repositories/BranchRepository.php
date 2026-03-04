<?php

namespace App\Modules\Administrator\Barbershop\Repositories;

use App\Models\Barbershop\Branch;

class BranchRepository
{
    public function dataTable($request)
    {
        return Branch::dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        $branch = Branch::updateOrCreate(['id' => $data['id']], $data);
        return $branch;
    }

    public function delete(int $id)
    {
        $branch = Branch::find($id);

        if ($branch->serviceBranches()->exists()) {
            throw new \Exception('No se puede eliminar la sucursal porque tiene servicios relacionados');
        }

        $branch->delete();
        return $branch;
    }

    public function getActiveBranches()
    {
        return Branch::where('is_active', true)->get();
    }
}
