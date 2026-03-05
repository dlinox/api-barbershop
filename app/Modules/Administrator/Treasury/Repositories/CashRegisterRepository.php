<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\CashRegister;

class CashRegisterRepository
{
    public function dataTable($request, int $infrastructureId)
    {
        return CashRegister::where('infrastructure_id', $infrastructureId)
            ->dataTable($request);
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
        return CashRegister::where('infrastructure_id', $infrastructureId)
            ->where('is_active', true)
            ->get();
    }
}
