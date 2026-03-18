<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Modules\Administrator\Treasury\Repositories\EmployeeAdvanceRepository;
use Illuminate\Http\Request;

class EmployeeAdvanceService
{
    public function __construct(
        private EmployeeAdvanceRepository $employeeAdvanceRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->employeeAdvanceRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->employeeAdvanceRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->employeeAdvanceRepository->delete($id);
    }
}
