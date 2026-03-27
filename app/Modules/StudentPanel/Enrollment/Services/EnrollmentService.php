<?php

namespace App\Modules\StudentPanel\Enrollment\Services;

use App\Modules\StudentPanel\Enrollment\Repositories\EnrollmentRepository;
use App\Modules\StudentPanel\Shared\StudentContext;
use Illuminate\Support\Collection;

class EnrollmentService
{
    public function __construct(
        private readonly EnrollmentRepository $repository,
    ) {}

    public function list(): Collection
    {
        return $this->repository->list(StudentContext::studentId());
    }
}
