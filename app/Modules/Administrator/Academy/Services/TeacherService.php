<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\TeacherRepository;
use App\Modules\Administrator\Academy\Repositories\Actions\CreateOrUpdateTeacherAction;
use App\Modules\Administrator\Academy\Repositories\Queries\TeacherPaymentCalculationQuery;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;

class TeacherService
{
    public function __construct(
        private TeacherRepository $teacherRepository,
        private CreateOrUpdateTeacherAction $createOrUpdateTeacherAction,
        private TeacherPaymentCalculationQuery $teacherPaymentCalculationQuery,
    ) {}

    public function dataTable($request)
    {
        return $this->teacherRepository->dataTable($request);
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
        $teacher = $this->teacherRepository->findByPersonId($data['id']);
        if (!$teacher) throw new \App\Common\Exceptions\ApiException('Docente no encontrado');

        $createOrUpdateUserAction = app(CreateOrUpdateUserAction::class);
        $createOrUpdateUserAction->execute($data['user']);

        $profileRepository = app(\App\Modules\Shared\Repositories\ProfileRepository::class);
        $teacherProfile = $profileRepository->findUserIdAndType($data['user']['id'], 'teachers');
        if ($teacherProfile) {
            $teacherProfile->update(['is_active' => $data['user']['is_active']]);
        }
    }

    public function selectAsyncItems($request)
    {
        return $this->teacherRepository->selectAsyncItems($request->search, $request->branchId);
    }
}
