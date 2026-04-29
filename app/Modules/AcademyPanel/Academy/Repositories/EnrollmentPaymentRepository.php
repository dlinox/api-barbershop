<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Academy\EnrollmentPayment;
use App\Common\Http\Context\AdminContext;

class EnrollmentPaymentRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::academyBranchId();

        $query = EnrollmentPayment::select(
            'academy_enrollment_payments.*',
            'core_persons.name as student_name',
            'core_persons.paternal_surname as student_paternal_surname',
            'academy_groups.name as group_name',
            'academy_levels.name as level_name',
        )
            ->join('academy_enrollments', 'academy_enrollment_payments.enrollment_id', '=', 'academy_enrollments.id')
            ->join('core_persons', 'academy_enrollments.profile_student_id', '=', 'core_persons.id')
            ->join('academy_groups', 'academy_enrollments.group_id', '=', 'academy_groups.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->where('academy_groups.branch_id', $branchId);

        if (empty($request->sortBy)) { $query->orderBy('academy_enrollment_payments.id', 'desc'); }
        return $query->dataTable($request);
    }

    public function historyByEnrollmentId(int $enrollmentId)
    {
        return EnrollmentPayment::select(
            'academy_enrollment_payments.id',
            'academy_enrollment_payment_details.type',
            'treasury_incomes.subtotal',
            'treasury_incomes.discount',
            'treasury_incomes.total',
            'treasury_incomes.receipt_serie',
            'treasury_incomes.receipt_number',
            'treasury_incomes.observations',
            'treasury_incomes.transaction_date',
            'treasury_incomes.status'
        )
            ->join('academy_enrollment_payment_details', 'academy_enrollment_payments.id', '=', 'academy_enrollment_payment_details.enrollment_payment_id')
            ->join('treasury_incomes', function ($join) {
                $join->on('treasury_incomes.transactionable_id', '=', 'academy_enrollment_payments.id')
                    ->where('treasury_incomes.transactionable_type', 'academy_enrollment_payments');
            })
            ->where('academy_enrollment_payments.enrollment_id', $enrollmentId)
            ->orderBy('academy_enrollment_payments.id', 'desc')
            ->get();
    }

    public function createOrUpdate(array $data)
    {
        return EnrollmentPayment::updateOrCreate(['id' => $data['id'] ?? null], $data);
    }

    public function delete(int $id)
    {
        $payment = EnrollmentPayment::findOrFail($id);
        $payment->delete();
        return $payment;
    }
}