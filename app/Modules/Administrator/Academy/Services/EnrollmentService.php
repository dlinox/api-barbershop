<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use App\Models\Core\Company;
use App\Common\Helpers\PdfHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Modules\Administrator\Academy\Repositories\EnrollmentRepository;
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

    public function dataTable($request)
    {
        return $this->enrollmentRepository->dataTable($request);
    }

    public function saveWithoutPayment($data)
    {
        try {
            DB::beginTransaction();

            $enrollmentData = [
                'id' => $data['id'] ?? null,
                'profile_student_id' => $data['student_id'],
                'group_id' => $data['group_id'],
                'date' => $data['date'],
            ];

            $enrollment = $this->enrollmentRepository->save($enrollmentData);

            $group = Group::with('branch.infrastructure')->findOrFail($data['group_id']);
            $infrastructureId = $group->branch->getInfrastructureId();

            $this->syncEnrollmentMaterialsAction->execute(
                $enrollment->id,
                $data['materials'] ?? [],
                $infrastructureId,
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function save($data)
    {

        try {
            DB::beginTransaction();

            $enrollmentData = [
                'id' => $data['id'],
                'profile_student_id' => $data['student_id'],
                'group_id' => $data['group_id'],
                'date' => $data['date'],
            ];

            $enrollment = $this->enrollmentRepository->save($enrollmentData);

            // Obtener el infrastructure_id para el grupo
            $group = Group::with('branch.infrastructure')->findOrFail($data['group_id']);
            $infrastructureId = $group->branch->getInfrastructureId();

            // ─── Sincronizar materiales ───
            $this->syncEnrollmentMaterialsAction->execute(
                $enrollment->id,
                $data['materials'] ?? [],
                $infrastructureId,
            );

            $payments = $data['payments'];

            $income = null;

            if (count($payments) > 0) {

                $enrollmentPayment = $enrollment->payments()->create([]);

                foreach ($payments as $payment) {
                    $enrollmentPayment->details()->create(
                        [
                            'enrollment_payment_id' => $enrollmentPayment->id,
                            'group_payment_plan_id' => $payment['plan_id'],
                            'type' => $payment['type'],
                            'subtotal' => $payment['subtotal'],
                            'discount' => $payment['discount'],
                            'total' => $payment['total'],
                        ]
                    );
                }

                // ─── Crear el income (comprobante de ingreso) ───
                $income = $this->createIncomeAction->execute(
                    data: $data['income'],
                    infrastructureId: $infrastructureId,
                    transactionableType: 'academy_enrollment_payments',
                    transactionableId: $enrollmentPayment->id,
                );

                // ─── Marcar adelantos como usados ───
                if (!empty($data['advance_ids'])) {
                    $this->advanceRepository->markAsUsed($data['advance_ids'], $enrollmentPayment->id);
                }
            }

            DB::commit();

            // ─── Generar PDF del comprobante (fuera de la transacción) ───
            if ($income) {
                $this->generateIncomePdfAction->execute($income->id);
            }

            return [
                'incomeId' => $income?->id,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function registerPayment($data)
    {
        try {
            DB::beginTransaction();

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

            // ─── Crear el income (comprobante de ingreso) ───
            $enrollment = Enrollment::findOrFail($data['enrollment_id']);
            $group = Group::with('branch.infrastructure')->findOrFail($enrollment->group_id);
            $infrastructureId = $group->branch->getInfrastructureId();

            $income = $this->createIncomeAction->execute(
                data: $data['income'],
                infrastructureId: $infrastructureId,
                transactionableType: 'academy_enrollment_payments',
                transactionableId: $enrollmentPayment->id,
            );

            // ─── Marcar adelantos como usados ───
            if (!empty($data['advance_ids'])) {
                $this->advanceRepository->markAsUsed($data['advance_ids'], $enrollmentPayment->id);
            }

            DB::commit();

            // ─── Generar PDF del comprobante (fuera de la transacción) ───
            $this->generateIncomePdfAction->execute($income->id);

            return [
                'incomeId' => $income->id,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getEnrollment($id)
    {
        return $this->enrollmentRepository->getEnrollment($id);
    }

    public function update($data)
    {
        $this->updateEnrollmentAction->execute($data);
    }

    public function generatePdf($id)
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

    public function getDetail($id)
    {
        return ($this->enrollmentDetailQuery)($id);
    }
}
