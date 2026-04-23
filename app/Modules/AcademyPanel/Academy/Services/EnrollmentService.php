<?php

namespace App\Modules\AcademyPanel\Academy\Services;

use App\Models\Academy\Group;
use App\Models\Core\Company;
use App\Common\Helpers\PdfHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Modules\AcademyPanel\Academy\Repositories\EnrollmentRepository;
use App\Modules\Administrator\Academy\Repositories\EnrollmentPaymentRepository;
use App\Modules\Administrator\Treasury\Repositories\Actions\CreateIncomeAction;
use App\Modules\Administrator\Academy\Repositories\Actions\UpdateEnrollmentAction;
use App\Modules\Administrator\Academy\Repositories\Actions\SyncEnrollmentMaterialsAction;
use App\Modules\Administrator\Academy\Repositories\Queries\EnrollmentDetailQuery;
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
        private EnrollmentPaymentAdvanceRepository $advanceRepository,
    ) {}

    public function dataTable($request) { return $this->enrollmentRepository->dataTable($request); }

    public function getEnrollment(int $id) { return $this->enrollmentRepository->getEnrollment($id); }

    public function detail(int $id) { return ($this->enrollmentDetailQuery)($id); }

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
                $this->createIncomeAction->execute(
                    data: $data['income'],
                    infrastructureId: $infrastructureId,
                    transactionableType: 'academy_enrollments',
                    transactionableId: $enrollment->id,
                );
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
            $enrollmentPayment = $this->enrollmentPaymentRepository->create([
                'enrollment_id' => $data['enrollment_id'],
            ]);

            foreach ($data['payments'] as $payment) {
                $enrollmentPayment->details()->create([
                    'group_payment_plan_id' => $payment['plan_id'],
                    'type' => $payment['type'],
                    'subtotal' => $payment['subtotal'],
                    'discount' => $payment['discount'],
                    'total' => $payment['total'],
                ]);
            }

            $group = Group::with('branch.infrastructure')
                ->join('academy_enrollments', 'academy_groups.id', '=', 'academy_enrollments.group_id')
                ->where('academy_enrollments.id', $data['enrollment_id'])
                ->select('academy_groups.*')->first();
            $infrastructureId = $group->branch->getInfrastructureId();

            $income = $this->createIncomeAction->execute(
                data: $data['income'],
                infrastructureId: $infrastructureId,
                transactionableType: 'academy_enrollment_payments',
                transactionableId: $enrollmentPayment->id,
            );

            if (!empty($data['advance_ids'])) {
                $this->advanceRepository->markAsUsed($data['advance_ids'], $enrollmentPayment->id);
            }

            DB::commit();

            return ['incomeId' => $income->id];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function generatePdf(int $id)
    {
        $enrollmentLoaded = ($this->enrollmentDetailQuery)($id);

        $data = array_merge(
            [
                'company' => Company::first(),
                'generated_by' => Auth::user()?->username ?? 'Sistema',
                'generated_at' => now()->format('d/m/Y H:i'),
            ],
            $this->enrollmentDetailQuery->toBladeData($enrollmentLoaded),
        );

        $html = View::make('enrollments.registration-certificate', $data)->render();
        $headerHtml = View::make('enrollments.common.header', $data)->render();
        $footerHtml = View::make('enrollments.common.footer', $data)->render();

        $mpdf = PdfHelper::createFromHtml($html, config: [
            'margin_top'    => 35,
            'margin_header' => 8,
            'margin_bottom' => 18,
            'margin_footer' => 8,
        ], headerHtml: $headerHtml, footerHtml: $footerHtml);

        $pdfContent = $mpdf->Output('', 'S');

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"ficha-matricula-{$id}.pdf\"",
        ]);
    }
}