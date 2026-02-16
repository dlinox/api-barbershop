<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\StudentRepository;
use App\Modules\Administrator\Academy\Repositories\Actions\CreateOrUpdateStudentAction;

class StudentService
{
    public function __construct(
        private StudentRepository $studentRepository,
        private CreateOrUpdateStudentAction $createOrUpdateStudentAction,
    ) {}

    public function dataTable($request)
    {
        return $this->studentRepository->dataTable($request);
    }

    public function save($data)
    {
        return $this->createOrUpdateStudentAction->execute($data);
    }

    public function selectAsyncItems($request)
    {
        return $this->studentRepository->selectAsyncItems($request);
    }
}
