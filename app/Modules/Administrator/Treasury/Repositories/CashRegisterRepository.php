<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\CashRegister;
use App\Common\Traits\HasInfrastructureScope;

class CashRegisterRepository
{
    use HasInfrastructureScope;

    public function dataTable($request, int $infrastructureId)
    {
        $this->validateInfrastructureAccess($infrastructureId);

        $query = CashRegister::where('infrastructure_id', $infrastructureId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return CashRegister::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $cashRegister = CashRegister::findOrFail($id);

        if ($cashRegister->sessions()->count() > 0) {
            throw new \Exception('No se puede eliminar la caja porque tiene sesiones registradas');
        }

        $cashRegister->delete();
        return $cashRegister;
    }

    public function getActiveCashRegisters(int $infrastructureId)
    {
        $this->validateInfrastructureAccess($infrastructureId);

        return CashRegister::where('infrastructure_id', $infrastructureId)
            ->where('is_active', true)
            ->get();
    }
}
