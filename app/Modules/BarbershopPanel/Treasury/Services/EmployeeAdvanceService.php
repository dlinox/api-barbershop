<?php

namespace App\Modules\BarbershopPanel\Treasury\Services;

use App\Modules\BarbershopPanel\Treasury\Repositories\WorkerAdvanceRepository;
use App\Modules\BarbershopPanel\Treasury\Repositories\BarberAdvanceRepository;

class EmployeeAdvanceService
{
    public function __construct(
        private readonly WorkerAdvanceRepository $workerAdvanceRepository,
        private readonly BarberAdvanceRepository $barberAdvanceRepository,
    ) {}

    public function workerDataTable($request)
    {
        return $this->workerAdvanceRepository->dataTable($request);
    }

    public function barberDataTable($request)
    {
        return $this->barberAdvanceRepository->dataTable($request);
    }

    public function save(array $data)
    {
        $employeeType = $data['employee_type'] ?? null;

        return match ($employeeType) {
            'profile_workers' => $this->workerAdvanceRepository->createOrUpdate($data),
            'profile_barbers' => $this->barberAdvanceRepository->createOrUpdate($data),
            default => throw new \Exception('Tipo de empleado no válido'),
        };
    }

    public function delete(int $id): void
    {
        // Determine which repo to call based on the record itself
        $advance = \App\Models\Treasury\EmployeeAdvance::findOrFail($id);
        match ($advance->employee_type) {
            'profile_workers' => $this->workerAdvanceRepository->delete($id),
            'profile_barbers' => $this->barberAdvanceRepository->delete($id),
            default => throw new \Exception('Tipo de empleado no válido'),
        };
    }
}
