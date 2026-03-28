<?php

namespace App\Modules\BarberPanel\Payment\Repositories;

use App\Models\Treasury\EmployeePayment;

class PaymentRepository
{
    public function dataTable($request, int $barberId)
    {
        $query = EmployeePayment::select(
            'treasury_employee_payments.id',
            'treasury_employee_payments.period',
            'treasury_employee_payments.period_start',
            'treasury_employee_payments.period_end',
            'treasury_employee_payments.base_amount',
            'treasury_employee_payments.bonus',
            'treasury_employee_payments.deductions',
            'treasury_employee_payments.total_amount',
            'treasury_employee_payments.status',
            'treasury_employee_payments.payment_date',
        )
            ->where('treasury_employee_payments.employee_type', 'barber')
            ->where('treasury_employee_payments.employee_id', $barberId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('treasury_employee_payments.period_start', 'desc');
        }

        return $query->dataTable($request, [
            'treasury_employee_payments.period',
        ]);
    }
}
