<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\StudentRepository;
use App\Modules\Administrator\Academy\Repositories\Actions\CreateOrUpdateStudentAction;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;
use App\Modules\Shared\Repositories\ProfileRepository;

class StudentService
{
    public function __construct(
        private StudentRepository $studentRepository,
        private CreateOrUpdateStudentAction $createOrUpdateStudentAction,
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
        private ProfileRepository $profileRepository,
    ) {}

    public function dataTable($request)
    {
        return $this->studentRepository->dataTable($request);
    }

    public function userDataTable($request)
    {
        return $this->studentRepository->userDataTable($request);
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
        $this->createOrUpdateUserAction->execute($data['user']);

        $profile = $this->profileRepository->findByProfileableIdAndType($data['id'], 'students');
        if ($profile) {
            $profile->update(['is_active' => $data['user']['is_active']]);
        }
    }
}
