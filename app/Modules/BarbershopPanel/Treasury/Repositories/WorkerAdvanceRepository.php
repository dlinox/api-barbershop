<?php

namespace App\Modules\BarbershopPanel\Treasury\Repositories;

use App\Models\Treasury\EmployeeAdvance;
use App\Models\Core\PaymentMethods;
use App\Common\Http\Context\AdminContext;

class WorkerAdvanceRepository
{
    public function dataTable($request)
    {
        $infrastructureId = AdminContext::infrastructureId();

        $query = EmployeeAdvance::select('treasury_employee_advances.*')
            ->with(['employee.person', 'paymentMethod'])
            ->join('profile_workers', 'profile_workers.id', '=', 'treasury_employee_advances.employee_id')
            ->where('treasury_employee_advances.employee_type', 'profile_workers')
            ->where('profile_workers.infrastructure_id', $infrastructureId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('treasury_employee_advances.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data): EmployeeAdvance
    {
        if (isset($data['id']) && $data['id']) {
            $advance = EmployeeAdvance::findOrFail($data['id']);
            if ($advance->status === 'discounted' || $advance->discounted_in_payment_id) {
                throw new \Exception('No se puede editar un adelanto que ya fue aplicado o descontado');
            }
        }
        return EmployeeAdvance::updateOrCreate(['id' => $data['id'] ?? null], $data);
    }

    public function delete(int $id): void
    {
        $advance = EmployeeAdvance::findOrFail($id);
        if ($advance->status === 'discounted' || $advance->discounted_in_payment_id) {
            throw new \Exception('No se puede eliminar un adelanto que ya fue aplicado o descontado');
        }
        $advance->delete();
    }
}
