<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Academy\EnrollmentPayment;

class EnrollmentPaymentRepository
{
    public function dataTable($request)
    {
        $query = EnrollmentPayment::select(
            'academy_enrollment_payments.id',
            'academy_enrollment_payments.enrollment_id',
            'academy_enrollment_payment_details.group_payment_plan_id',
            'academy_enrollment_payment_details.type',
            'academy_enrollment_payment_details.subtotal',
            'academy_enrollment_payment_details.discount',
            'academy_enrollment_payment_details.total',
            'academy_enrollment_payments.created_at',

            // student
            'academy_enrollments.profile_student_id as student_id',
            'core_persons.name as student_person_name',
            'core_persons.paternal_surname as student_person_paternal_surname',
            'core_persons.maternal_surname as student_person_maternal_surname',
            'core_persons.document_number as student_person_document_number',

            // group
            'academy_enrollments.group_id',
            'academy_groups.name as group_name',
            'academy_groups.start_date as group_start_date',
            'academy_groups.end_date as group_end_date',

            // level
            'academy_levels.name as group_level_name',

            // payment plan
            'academy_group_payment_plans.start_date as plan_start_date',
            'academy_group_payment_plans.end_date as plan_end_date',
        )
            ->join('academy_enrollment_payment_details', 'academy_enrollment_payments.id', '=', 'academy_enrollment_payment_details.enrollment_payment_id')
            ->join('academy_enrollments', 'academy_enrollment_payments.enrollment_id', '=', 'academy_enrollments.id')
            ->join('core_persons', 'academy_enrollments.profile_student_id', '=', 'core_persons.id')
            ->join('academy_groups', 'academy_enrollments.group_id', '=', 'academy_groups.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->join('academy_group_payment_plans', 'academy_enrollment_payment_details.group_payment_plan_id', '=', 'academy_group_payment_plans.id');

        return $query->dataTable($request);
    }

    public function create($data)
    {
        return EnrollmentPayment::create($data);
    }

    public function save($data)
    {
        return EnrollmentPayment::updateOrCreate(
            ['id' => $data['id'] ?? null],
            ['enrollment_id' => $data['enrollment_id']]
        );
    }

    public function delete(int $id)
    {
        $payment = EnrollmentPayment::findOrFail($id);
        return $payment->delete();
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
            ->get();
    }
}
