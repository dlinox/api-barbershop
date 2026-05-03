<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Treasury\EmployeePayment;
use App\Modules\Administrator\Treasury\Repositories\EmployeePaymentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeePaymentService
{
    public function __construct(
        private EmployeePaymentRepository $employeePaymentRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->employeePaymentRepository->dataTable($request);
    }

    public function save(array $data): \App\Models\Treasury\EmployeePayment
    {
        $this->validateNoOverlappingPeriod($data);
        $data['period'] = $this->formatPeriod($data['period_start'], $data['period_end']);
        return $this->employeePaymentRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->employeePaymentRepository->delete($id);
    }

    private function validateNoOverlappingPeriod(array $data): void
    {
        $query = EmployeePayment::where('employee_type', $data['employee_type'])
            ->where('employee_id', $data['employee_id'])
            ->where('status', 'paid')
            ->where('period_start', '<=', $data['period_end'])
            ->where('period_end', '>=', $data['period_start']);

        if (!empty($data['id'])) {
            $query->where('id', '!=', $data['id']);
        }

        if ($query->exists()) {
            throw new ApiException('Ya existe un pago registrado que se solapa con el rango de fechas seleccionado.');
        }
    }

    private function formatPeriod(string $periodStart, string $periodEnd): string
    {
        $start = Carbon::parse($periodStart);
        $end = Carbon::parse($periodEnd);

        $months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        if ($start->month === $end->month && $start->year === $end->year) {
            return $months[$start->month - 1] . ' ' . $start->year;
        }

        if ($start->year !== $end->year) {
            return $start->day . ' ' . $months[$start->month - 1] . ' ' . $start->year
                . ' - ' . $end->day . ' ' . $months[$end->month - 1] . ' ' . $end->year;
        }

        return $start->day . ' ' . $months[$start->month - 1]
            . ' - ' . $end->day . ' ' . $months[$end->month - 1] . ' ' . $end->year;
    }
}
