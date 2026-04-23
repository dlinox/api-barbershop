<?php

namespace App\Modules\AcademyPanel\Academy\Services;

use App\Modules\AcademyPanel\Academy\Repositories\TeacherRepository;
use App\Modules\Administrator\Academy\Repositories\Actions\CreateOrUpdateTeacherAction;

class TeacherService
{
    public function __construct(
        private TeacherRepository $teacherRepository,
        private CreateOrUpdateTeacherAction $createOrUpdateTeacherAction,
    ) {}

    public function dataTable($request) { return $this->teacherRepository->dataTable($request); }
    public function save(array $data) { return $this->createOrUpdateTeacherAction->execute($data); }
    public function selectAsyncItems($request) { return $this->teacherRepository->selectAsyncItems($request->search); }
}