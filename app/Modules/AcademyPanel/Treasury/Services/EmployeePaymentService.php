<?php

namespace App\Modules\AcademyPanel\Treasury\Services;

use App\Modules\AcademyPanel\Treasury\Repositories\WorkerPaymentRepository;
use App\Modules\AcademyPanel\Treasury\Repositories\TeacherPaymentRepository;

class EmployeePaymentService
{
    public function __construct(
        private readonly WorkerPaymentRepository $workerPaymentRepository,
        private readonly TeacherPaymentRepository $teacherPaymentRepository,
    ) {}

    public function workerDataTable($request)
    {
        return $this->workerPaymentRepository->dataTable($request);
    }

    public function teacherDataTable($request)
    {
        return $this->teacherPaymentRepository->dataTable($request);
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
