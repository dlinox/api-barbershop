<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Modules\Administrator\Treasury\Repositories\EmployeePaymentRepository;
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

    public function save(array $data)
    {
        return $this->employeePaymentRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->employeePaymentRepository->delete($id);
    }
}
