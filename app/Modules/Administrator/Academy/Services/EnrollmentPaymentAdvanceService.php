<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\EnrollmentPaymentAdvanceRepository;

class EnrollmentPaymentAdvanceService
{
    public function __construct(
        private EnrollmentPaymentAdvanceRepository $repository
    ) {}

    public function getAvailableByStudentId(int $studentId)
    {
        return $this->repository->getAvailableByStudentId($studentId);
    }

    public function save(array $data)
    {
        return $this->repository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
