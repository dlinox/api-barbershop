<?php

namespace App\Modules\AcademyPanel\Treasury\Services;

use App\Modules\AcademyPanel\Treasury\Repositories\WorkerAdvanceRepository;
use App\Modules\AcademyPanel\Treasury\Repositories\TeacherAdvanceRepository;

class EmployeeAdvanceService
{
    public function __construct(
        private readonly WorkerAdvanceRepository $workerAdvanceRepository,
        private readonly TeacherAdvanceRepository $teacherAdvanceRepository,
    ) {}

    public function workerDataTable($request)
    {
        return $this->workerAdvanceRepository->dataTable($request);
    }

    public function teacherDataTable($request)
    {
        return $this->teacherAdvanceRepository->dataTable($request);
    }

    public function save(array $data)
    {
        $employeeType = $data['employee_type'] ?? null;

        return match ($employeeType) {
            'profile_workers'  => $this->workerAdvanceRepository->createOrUpdate($data),
            'profile_teachers' => $this->teacherAdvanceRepository->createOrUpdate($data),
            default => throw new \Exception('Tipo de empleado no válido'),
        };
    }

    public function delete(int $id): void
    {
        $advance = \App\Models\Treasury\EmployeeAdvance::findOrFail($id);
        match ($advance->employee_type) {
            'profile_workers'  => $this->workerAdvanceRepository->delete($id),
            'profile_teachers' => $this->teacherAdvanceRepository->delete($id),
            default => throw new \Exception('Tipo de empleado no válido'),
        };
    }
}
