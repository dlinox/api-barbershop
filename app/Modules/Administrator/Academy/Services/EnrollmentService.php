<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use Illuminate\Support\Facades\DB;
use App\Modules\Administrator\Academy\Repositories\EnrollmentRepository;
use App\Modules\Administrator\Academy\Repositories\EnrollmentPaymentRepository;
use App\Modules\Administrator\Treasury\Repositories\Actions\CreateIncomeAction;
use App\Modules\Administrator\Academy\Repositories\Actions\UpdateEnrollmentAction;
use App\Modules\Administrator\Academy\Repositories\Actions\SyncEnrollmentMaterialsAction;

class EnrollmentService
{
    public function __construct(
        private EnrollmentRepository $enrollmentRepository,
        private EnrollmentPaymentRepository $enrollmentPaymentRepository,
        private CreateIncomeAction $createIncomeAction,
        private UpdateEnrollmentAction $updateEnrollmentAction,
        private SyncEnrollmentMaterialsAction $syncEnrollmentMaterialsAction,
    ) {}

    public function dataTable($request)
    {
        return $this->enrollmentRepository->dataTable($request);
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
                $this->createIncomeAction->execute(
                    data: $data['income'],
                    infrastructureId: $infrastructureId,
                    transactionableType: 'academy_enrollment_payments',
                    transactionableId: $enrollmentPayment->id,
                );
            }

            DB::commit();
            return;
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

            $this->createIncomeAction->execute(
                data: $data['income'],
                infrastructureId: $infrastructureId,
                transactionableType: 'academy_enrollment_payments',
                transactionableId: $enrollmentPayment->id,
            );

            DB::commit();

            return;
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
}
