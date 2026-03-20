<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\TeacherRepository;
use App\Modules\Administrator\Academy\Repositories\Actions\CreateOrUpdateTeacherAction;
use App\Modules\Administrator\Academy\Repositories\Queries\TeacherPaymentCalculationQuery;

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

    public function selectAsyncItems($request)
    {
        return $this->teacherRepository->selectAsyncItems($request->search, $request->branchId);
    }
}
