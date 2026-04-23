<?php

namespace App\Modules\AcademyPanel\Treasury\Repositories;

use App\Models\Treasury\EmployeePayment;
use App\Models\Treasury\EmployeeAdvance;
use App\Models\Profile\Teacher;
use App\Common\Http\Context\AdminContext;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

class TeacherPaymentRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::academyBranchId();

        $query = EmployeePayment::select('treasury_employee_payments.*')
            ->with([
                'employee' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([Teacher::class => ['branch']]);
                },
                'paymentMethod',
                'paidBy',
            ])
            ->join('profile_teachers', 'profile_teachers.core_person_id', '=', 'treasury_employee_payments.employee_id')
            ->where('treasury_employee_payments.employee_type', 'profile_teachers')
            ->where('profile_teachers.branch_id', $branchId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('treasury_employee_payments.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data): EmployeePayment
    {
        if (isset($data['id']) && $data['id']) {
            $payment = EmployeePayment::findOrFail($data['id']);
            if ($payment->status === 'cancelled') {
                throw new \Exception('No se puede editar un pago cancelado');
            }
        }

        $advanceIds = $data['calculation_details']['advance_ids'] ?? [];

        try {
            DB::beginTransaction();
            $payment = EmployeePayment::updateOrCreate(['id' => $data['id'] ?? null], $data);
            if (!empty($advanceIds)) {
                EmployeeAdvance::whereIn('id', $advanceIds)
                    ->where('status', 'pending')
                    ->update(['status' => 'discounted', 'discounted_in_payment_id' => $payment->id]);
            }
            DB::commit();
            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        $payment = EmployeePayment::findOrFail($id);
        if ($payment->status === 'cancelled') {
            throw new \Exception('No se puede eliminar un pago cancelado');
        }
        $payment->delete();
    }
}
