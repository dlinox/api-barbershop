<?php

namespace App\Modules\AcademyPanel\Treasury\Repositories;

use App\Models\Treasury\EmployeeAdvance;
use App\Models\Profile\Teacher;
use App\Common\Http\Context\AdminContext;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TeacherAdvanceRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::academyBranchId();

        $query = EmployeeAdvance::select('treasury_employee_advances.*')
            ->with([
                'employee' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([Teacher::class => ['branch']]);
                },
                'paymentMethod',
            ])
            ->join('profile_teachers', 'profile_teachers.core_person_id', '=', 'treasury_employee_advances.employee_id')
            ->where('treasury_employee_advances.employee_type', 'profile_teachers')
            ->where('profile_teachers.branch_id', $branchId);

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
