<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Common\Helpers\DateHelper;
use App\Common\Enums\DayOfWeek;
use App\Models\Academy\Enums\Shift;
use App\Models\Academy\Enrollment;
use App\Models\Academy\EnrollmentGroupChange;
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

        $mpdf = PdfHelper::create(config: [
            'margin_top'    => 35,
            'margin_header' => 8,
            'margin_bottom' => 18,
            'margin_footer' => 8,
        ]);
        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetHTMLFooter($footerHtml);

        if ($enrollmentLoaded->status === 'cancelled') {
            $mpdf->SetWatermarkText('CANCELADO', 0.1);
            $mpdf->showWatermarkText = true;
        }

        $mpdf->WriteHTML($html);

        $pdfContent = $mpdf->Output('', 'S');

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"ficha-matricula-{$id}.pdf\"",
        ]);
    }

    public function generateGroupChangePdf(int $id)
    {
        $change = EnrollmentGroupChange::with([
            'originEnrollment.student.person.documentTypeRelation',
            'originEnrollment.group.level',
            'originEnrollment.group.branch',
            'originEnrollment.group.schedule',
            'originEnrollment.group.paymentPlans',
            'originEnrollment.payments.details',
            'destinationEnrollment.group.level',
            'destinationEnrollment.group.branch',
            'destinationEnrollment.group.schedule',
            'destinationEnrollment.group.paymentPlans',
            'destinationEnrollment.payments.details',
            'changedByUser',
        ])->findOrFail($id);

        $originEnrollment = $change->originEnrollment;
        $originGroup = $originEnrollment->group;
        $destEnrollment = $change->destinationEnrollment;
        $destGroup = $destEnrollment->group;
        $person = $originEnrollment->student->person;

        $originPaidPlanIds = $originEnrollment->payments
            ->flatMap(fn($p) => $p->details)
            ->pluck('group_payment_plan_id')
            ->toArray();

        $originPaymentPlans = $originGroup->paymentPlans
            ->sortBy(fn($p) => [$p->type === 'enrollment' ? 0 : 1, $p->start_date])
            ->values()
            ->map(fn($plan) => [
                'type'       => $plan->type === 'enrollment' ? 'Matrícula' : 'Mensualidad',
                'start_date' => $plan->start_date ? \Carbon\Carbon::parse($plan->start_date)->format('d/m/Y') : '—',
                'end_date'   => $plan->end_date ? \Carbon\Carbon::parse($plan->end_date)->format('d/m/Y') : '—',
                'amount'     => (float) $plan->amount,
                'is_paid'    => in_array($plan->id, $originPaidPlanIds),
            ])
            ->all();

        $paidPlanIds = $destEnrollment->payments
            ->flatMap(fn($p) => $p->details)
            ->pluck('group_payment_plan_id')
            ->toArray();

        $paymentPlans = $destGroup->paymentPlans
            ->sortBy(fn($p) => [$p->type === 'enrollment' ? 0 : 1, $p->start_date])
            ->values()
            ->map(fn($plan) => [
                'type'       => $plan->type === 'enrollment' ? 'Matrícula' : 'Mensualidad',
                'start_date' => $plan->start_date ? \Carbon\Carbon::parse($plan->start_date)->format('d/m/Y') : '—',
                'end_date'   => $plan->end_date ? \Carbon\Carbon::parse($plan->end_date)->format('d/m/Y') : '—',
                'amount'     => (float) $plan->amount,
                'is_paid'    => in_array($plan->id, $paidPlanIds),
            ])
            ->all();

        $data = [
            'company'       => Company::first(),
            'branch'        => $destGroup->branch ?? $originGroup->branch ?? null,
            'generated_by'  => Auth::user()?->username ?? 'Sistema',
            'generated_at'  => now()->format('d/m/Y H:i'),
            'change_id'     => $change->id,
            'changed_at'    => $change->changed_at?->format('d/m/Y H:i'),
            'changed_by'    => $change->changedByUser?->username ?? 'Sistema',
            'reason'        => $change->reason ?? '—',
            // Student
            'student_full_name' => $person->full_name,
            'document_type'     => $person->documentTypeRelation?->name ?? '—',
            'document_number'   => $person->document_number,
            'phone'             => $person->phone ?? null,
            // Origin group
            'origin_enrollment_id'   => $originEnrollment->id,
            'origin_group_name'      => $originGroup->name,
            'origin_level_name'      => $originGroup->level?->name ?? '—',
            'origin_branch_name'     => $originGroup->branch?->name ?? '—',
            'origin_schedule_shift'  => $originGroup->schedule ? (Shift::tryFrom($originGroup->schedule->shift)?->label() ?? $originGroup->schedule->shift) : '—',
            'origin_schedule_time'   => $originGroup->schedule
                ? (substr($originGroup->schedule->start_time, 0, 5) . ' – ' . substr($originGroup->schedule->end_time, 0, 5))
                : '—',
            'origin_days_of_week'    => collect(explode(',', $originGroup->days_of_week ?? ''))->map(fn($d) => DayOfWeek::tryFrom(trim($d))?->label() ?? trim($d))->filter()->implode(', '),
            'origin_start_date'      => $originGroup->start_date ? \Carbon\Carbon::parse($originGroup->start_date)->format('d/m/Y') : '—',
            'origin_end_date'        => $originGroup->end_date ? \Carbon\Carbon::parse($originGroup->end_date)->format('d/m/Y') : '—',
            // Destination group
            'dest_enrollment_id'  => $destEnrollment->id,
            'dest_group_name'     => $destGroup->name,
            'dest_level_name'     => $destGroup->level?->name ?? '—',
            'dest_branch_name'    => $destGroup->branch?->name ?? '—',
            'dest_schedule_shift' => $destGroup->schedule ? (Shift::tryFrom($destGroup->schedule->shift)?->label() ?? $destGroup->schedule->shift) : '—',
            'dest_schedule_time'  => $destGroup->schedule
                ? (substr($destGroup->schedule->start_time, 0, 5) . ' – ' . substr($destGroup->schedule->end_time, 0, 5))
                : '—',
            'dest_days_of_week'   => collect(explode(',', $destGroup->days_of_week ?? ''))->map(fn($d) => DayOfWeek::tryFrom(trim($d))?->label() ?? trim($d))->filter()->implode(', '),
            'dest_start_date'     => $destGroup->start_date ? \Carbon\Carbon::parse($destGroup->start_date)->format('d/m/Y') : '—',
            'dest_end_date'       => $destGroup->end_date ? \Carbon\Carbon::parse($destGroup->end_date)->format('d/m/Y') : '—',
            // Payments
            'origin_payment_plans' => $originPaymentPlans,
            'payment_plans'        => $paymentPlans,
        ];

        $html       = View::make('enrollments.group-change-certificate', $data)->render();
        $headerHtml = View::make('enrollments.group-change-header', $data)->render();
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
            'Content-Disposition' => "inline; filename=\"cambio-grupo-{$id}.pdf\"",
        ]);
    }

    public function getDetail($id)
    {
        return ($this->enrollmentDetailQuery)($id);
    }
}
