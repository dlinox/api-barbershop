<?php

namespace App\Modules\StudentPanel\Payment\Repositories;

use App\Models\Academy\EnrollmentPayment;

class PaymentRepository
{
    public function dataTable($request, int $studentId)
    {
        $query = EnrollmentPayment::select(
            'academy_enrollment_payments.id',
            'academy_enrollment_payments.created_at',

            // detail
            'academy_enrollment_payment_details.type',
            'academy_enrollment_payment_details.subtotal',
            'academy_enrollment_payment_details.discount',
            'academy_enrollment_payment_details.total',

            // group
            'academy_groups.name as group_name',
            'academy_levels.name as group_level_name',

            // plan period
            'academy_group_payment_plans.start_date as plan_start_date',
            'academy_group_payment_plans.end_date as plan_end_date',

            // receipt
            'treasury_incomes.id as income_id',
            'treasury_incomes.receipt_serie',
            'treasury_incomes.receipt_number',
            'treasury_incomes.transaction_date',
            'treasury_incomes.status as income_status',
        )
            ->join('academy_enrollment_payment_details', 'academy_enrollment_payments.id', '=', 'academy_enrollment_payment_details.enrollment_payment_id')
            ->join('academy_enrollments', 'academy_enrollment_payments.enrollment_id', '=', 'academy_enrollments.id')
            ->join('academy_groups', 'academy_enrollments.group_id', '=', 'academy_groups.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->join('academy_group_payment_plans', 'academy_enrollment_payment_details.group_payment_plan_id', '=', 'academy_group_payment_plans.id')
            ->leftJoin('treasury_incomes', function ($join) {
                $join->on('treasury_incomes.transactionable_id', '=', 'academy_enrollment_payments.id')
                    ->where('treasury_incomes.transactionable_type', 'academy_enrollment_payments');
            })
            ->where('academy_enrollments.profile_student_id', $studentId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('academy_enrollment_payments.id', 'desc');
        }

        return $query->dataTable($request, [
            'academy_groups.name',
            'academy_levels.name',
        ]);
    }
}
