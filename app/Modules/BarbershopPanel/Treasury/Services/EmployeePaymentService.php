<?php

namespace App\Modules\BarbershopPanel\Treasury\Services;

use App\Modules\BarbershopPanel\Treasury\Repositories\WorkerPaymentRepository;
use App\Modules\BarbershopPanel\Treasury\Repositories\BarberPaymentRepository;

class EmployeePaymentService
{
    public function __construct(
        private readonly WorkerPaymentRepository $workerPaymentRepository,
        private readonly BarberPaymentRepository $barberPaymentRepository,
    ) {}

    public function workerDataTable($request)
    {
        return $this->workerPaymentRepository->dataTable($request);
    }

    public function barberDataTable($request)
    {
        return $this->barberPaymentRepository->dataTable($request);
    }

    public function workerPaymentSummary($request)
    {
        return $this->workerPaymentRepository->paymentSummaryDataTable($request);
    }

    public function barberPaymentSummary($request)
    {
        return $this->barberPaymentRepository->paymentSummaryDataTable($request);
    }

    public function workerPaymentCalculation(int $workerId, string $periodStart, string $periodEnd): array
    {
        $this->workerPaymentRepository->ensureBelongsToInfrastructure($workerId);
        return $this->workerPaymentRepository->paymentCalculation($workerId, $periodStart, $periodEnd);
    }

    public function barberPaymentCalculation(int $barberId, string $periodStart, string $periodEnd): array
    {
        $this->barberPaymentRepository->ensureBelongsToBranch($barberId);
        return $this->barberPaymentRepository->paymentCalculation($barberId, $periodStart, $periodEnd);
    }

    public function save(array $data)
    {
        $employeeType = $data['employee_type'] ?? null;

        return match ($employeeType) {
            'profile_workers' => $this->workerPaymentRepository->createOrUpdate($data),
            'profile_barbers' => $this->barberPaymentRepository->createOrUpdate($data),
            default => throw new \Exception('Tipo de empleado no válido'),
        };
    }

    public function delete(int $id): void
    {
        $payment = \App\Models\Treasury\EmployeePayment::findOrFail($id);
        match ($payment->employee_type) {
            'profile_workers' => $this->workerPaymentRepository->delete($id),
            'profile_barbers' => $this->barberPaymentRepository->delete($id),
            default => throw new \Exception('Tipo de empleado no válido'),
        };
    }
}
