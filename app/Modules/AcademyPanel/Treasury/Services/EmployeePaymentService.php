<?php

namespace App\Modules\AcademyPanel\Treasury\Services;

use App\Modules\AcademyPanel\Treasury\Repositories\WorkerPaymentRepository;
use App\Modules\AcademyPanel\Treasury\Repositories\TeacherPaymentRepository;
use App\Modules\Administrator\Academy\Repositories\Queries\TeacherPaymentCalculationQuery;

class EmployeePaymentService
{
    public function __construct(
        private readonly WorkerPaymentRepository $workerPaymentRepository,
        private readonly TeacherPaymentRepository $teacherPaymentRepository,
        private readonly TeacherPaymentCalculationQuery $teacherPaymentCalculationQuery,
    ) {}

    public function workerDataTable($request)
    {
        return $this->workerPaymentRepository->dataTable($request);
    }

    public function teacherDataTable($request)
    {
        return $this->teacherPaymentRepository->dataTable($request);
    }

    public function teacherPaymentSummary($request)
    {
        return $this->teacherPaymentRepository->paymentSummaryDataTable($request);
    }

    public function workerPaymentSummary($request)
    {
        return $this->workerPaymentRepository->paymentSummaryDataTable($request);
    }

    public function teacherPaymentCalculation(int $teacherId, string $periodStart, string $periodEnd): array
    {
        $this->teacherPaymentRepository->ensureBelongsToBranch($teacherId);
        return ($this->teacherPaymentCalculationQuery)($teacherId, $periodStart, $periodEnd);
    }

    public function workerPaymentCalculation(int $workerId, string $periodStart, string $periodEnd): array
    {
        return $this->workerPaymentRepository->paymentCalculation($workerId, $periodStart, $periodEnd);
    }

    public function save(array $data)
    {
        $employeeType = $data['employee_type'] ?? null;

        return match ($employeeType) {
            'profile_workers'  => $this->workerPaymentRepository->createOrUpdate($data),
            'profile_teachers' => $this->teacherPaymentRepository->createOrUpdate($data),
            default => throw new \Exception('Tipo de empleado no válido'),
        };
    }

    public function delete(int $id): void
    {
        $payment = \App\Models\Treasury\EmployeePayment::findOrFail($id);
        match ($payment->employee_type) {
            'profile_workers'  => $this->workerPaymentRepository->delete($id),
            'profile_teachers' => $this->teacherPaymentRepository->delete($id),
            default => throw new \Exception('Tipo de empleado no válido'),
        };
    }
}
