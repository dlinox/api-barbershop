<?php

namespace App\Modules\AcademyPanel\Academy\Services;

use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use App\Common\Helpers\PdfHelper;
use Illuminate\Support\Facades\DB;
use App\Modules\AcademyPanel\Academy\Repositories\EnrollmentRepository;
use App\Modules\Administrator\Academy\Repositories\EnrollmentPaymentRepository;
use App\Modules\Administrator\Treasury\Repositories\Actions\CreateIncomeAction;
use App\Modules\Administrator\Academy\Repositories\Actions\UpdateEnrollmentAction;
use App\Modules\Administrator\Academy\Repositories\Actions\SyncEnrollmentMaterialsAction;
use App\Modules\Administrator\Academy\Repositories\Queries\EnrollmentDetailQuery;
use App\Modules\Administrator\Treasury\Repositories\Actions\GenerateIncomePdfAction;
use App\Modules\Administrator\Academy\Repositories\EnrollmentPaymentAdvanceRepository;

class EnrollmentService
{
    public function __construct(
        private EnrollmentRepository $enrollmentRepository,
        private EnrollmentPaymentRepository $enrollmentPaymentRepository,
        private CreateIncomeAction $createIncomeAction,
        private UpdateEnrollmentAction $updateEnrollmentAction,
        private SyncEnrollmentMaterialsAction $syncEnrollmentMaterialsAction,
        private EnrollmentDetailQuery $enrollmentDetailQuery,
        private GenerateIncomePdfAction $generateIncomePdfAction,
        private EnrollmentPaymentAdvanceRepository $advanceRepository,
    ) {}

    public function dataTable($request) { return $this->enrollmentRepository->dataTable($request); }

    public function getEnrollment(int $id) { return $this->enrollmentRepository->getEnrollment($id); }

    public function detail(int $id) { return $this->enrollmentDetailQuery->execute($id); }

    public function saveWithoutPayment(array $data)
    {
        DB::beginTransaction();
        try {
            $enrollmentData = [
                'id' => $data['id'] ?? null,
                'profile_student_id' => $data['student_id'],
                'group_id' => $data['group_id'],
                'date' => $data['date'],
            ];

            $enrollment = $this->enrollmentRepository->save($enrollmentData);
            $group = Group::with('branch.infrastructure')->findOrFail($data['group_id']);
            $infrastructureId = $group->branch->getInfrastructureId();

            $this->syncEnrollmentMaterialsAction->execute($enrollment->id, $data['materials'] ?? [], $infrastructureId);
            DB::commit();
            return $enrollment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function save(array $data)
    {
        DB::beginTransaction();
        try {
            $enrollmentData = [
                'id' => $data['id'] ?? null,
                'profile_student_id' => $data['student_id'],
                'group_id' => $data['group_id'],
                'date' => $data['date'],
            ];

            $enrollment = $this->enrollmentRepository->save($enrollmentData);
            $group = Group::with('branch.infrastructure')->findOrFail($data['group_id']);
            $infrastructureId = $group->branch->getInfrastructureId();

            $this->syncEnrollmentMaterialsAction->execute($enrollment->id, $data['materials'] ?? [], $infrastructureId);

            if (!empty($data['income'])) {
                $incomeData = array_merge($data['income'], [
                    'transactionable_type' => 'academy_enrollments',
                    'transactionable_id' => $enrollment->id,
                    'infrastructure_id' => $infrastructureId,
                ]);
                $this->createIncomeAction->execute($incomeData);
            }

            DB::commit();
            return $enrollment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $data) { return $this->updateEnrollmentAction->execute($data); }

    public function registerPayment(array $data)
    {
        DB::beginTransaction();
        try {
            $group = Group::with('branch.infrastructure')
                ->join('academy_enrollments', 'academy_groups.id', '=', 'academy_enrollments.group_id')
                ->where('academy_enrollments.id', $data['enrollment_id'])
                ->select('academy_groups.*')->first();
            $infrastructureId = $group->branch->getInfrastructureId();

            $incomeData = array_merge($data['income'], [
                'transactionable_type' => 'academy_enrollment_payments',
                'infrastructure_id' => $infrastructureId,
            ]);
            $this->createIncomeAction->execute($incomeData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function generatePdf(int $id)
    {
        $enrollment = Enrollment::with(['group.branch', 'student.person'])->findOrFail($id);
        return $this->generateIncomePdfAction->execute($enrollment);
    }
}