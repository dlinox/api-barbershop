<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\TeacherRepository;
use App\Modules\Administrator\Academy\Repositories\Actions\CreateOrUpdateTeacherAction;
use App\Modules\Administrator\Academy\Repositories\Queries\TeacherPaymentCalculationQuery;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;
use App\Modules\Shared\Repositories\ProfileRepository;

class TeacherService
{
    public function __construct(
        private TeacherRepository $teacherRepository,
        private CreateOrUpdateTeacherAction $createOrUpdateTeacherAction,
        private TeacherPaymentCalculationQuery $teacherPaymentCalculationQuery,
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
        private ProfileRepository $profileRepository,
    ) {}

    public function dataTable($request)
    {
        return $this->teacherRepository->dataTable($request);
    }

    public function userDataTable($request)
    {
        return $this->teacherRepository->userDataTable($request);
    }

    public function paymentSummaryDataTable($request)
    {
        return $this->teacherRepository->paymentSummaryDataTable($request);
    }

    public function paymentCalculation(int $teacherId, string $periodStart, string $periodEnd): array
    {
        return ($this->teacherPaymentCalculationQuery)($teacherId, $periodStart, $periodEnd);
    }

    public function save($data)
    {
        return $this->createOrUpdateTeacherAction->execute($data);
    }

    public function saveUser(array $data): void
    {
        $this->createOrUpdateUserAction->execute($data['user']);

        $profile = $this->profileRepository->findByProfileableIdAndType($data['id'], 'teachers');
        if ($profile) {
            $profile->update(['is_active' => $data['user']['is_active']]);
        }
    }

    public function selectAsyncItems($request)
    {
        return $this->teacherRepository->selectAsyncItems($request->search, $request->branchId);
    }
}
