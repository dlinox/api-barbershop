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
        return $this->studentRepository->selectAsyncItems($request->search);
    }

    public function saveUser(array $data): void
    {
        $student = $this->studentRepository->findByPersonId($data['id']);
        if (!$student) throw new \App\Common\Exceptions\ApiException('Estudiante no encontrado');

        $createOrUpdateUserAction = app(\App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction::class);
        $createOrUpdateUserAction->execute($data['user']);

        $profileRepository = app(\App\Modules\Shared\Repositories\ProfileRepository::class);
        $studentProfile = $profileRepository->findUserIdAndType($data['user']['id'], 'students');
        if ($studentProfile) {
            $studentProfile->update(['is_active' => $data['user']['is_active']]);
        }
    }
}
