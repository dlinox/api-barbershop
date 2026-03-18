<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\EmployeeAdvance;
use App\Common\Traits\HasInfrastructureScope;

class EmployeeAdvanceRepository
{
    use HasInfrastructureScope;

    public function dataTable($request)
    {
        $query = EmployeeAdvance::with([
            'employee',
            'infrastructure',
            'cashSession',
            'paymentMethod',
            'authorizedBy',
            'paidBy',
            'discountedInPayment'
        ]);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id']) && $data['id']) {
            $advance = EmployeeAdvance::findOrFail($data['id']);

            if ($advance->status === 'discounted' || $advance->discounted_in_payment_id) {
                throw new \Exception('No se puede editar un adelanto que ya fue aplicado o descontado');
            }
        }

        return EmployeeAdvance::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $advance = EmployeeAdvance::findOrFail($id);

        if ($advance->status === 'discounted' || $advance->discounted_in_payment_id) {
            throw new \Exception('No se puede eliminar un adelanto que ya fue aplicado o descontado');
        }

        $advance->delete();
        return $advance;
    }
}
