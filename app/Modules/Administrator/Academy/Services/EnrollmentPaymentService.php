<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\EnrollmentPaymentRepository;

class EnrollmentPaymentService
{
    public function __construct(
        private EnrollmentPaymentRepository $enrollmentPaymentRepository
    ) {}

    public function dataTable($request)
    {
        return $this->enrollmentPaymentRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->enrollmentPaymentRepository->save($data);
    }

    public function delete(int $id)
    {
        return $this->enrollmentPaymentRepository->delete($id);
    }
}
