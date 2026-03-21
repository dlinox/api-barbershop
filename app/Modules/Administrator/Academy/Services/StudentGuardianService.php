<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\StudentGuardianRepository;

class StudentGuardianService
{
    public function __construct(
        private StudentGuardianRepository $studentGuardianRepository
    ) {}

    public function findByStudentId(int $studentId)
    {
        return $this->studentGuardianRepository->findByStudentId($studentId);
    }

    public function save(array $data)
    {
        return $this->studentGuardianRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->studentGuardianRepository->delete($id);
    }
}
