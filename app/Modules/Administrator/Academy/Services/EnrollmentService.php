<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Models\Academy\Branch;
use App\Models\Academy\Enrollment;
use App\Models\Academy\EnrollmentMaterial;
use App\Models\Academy\EnrollmentPayment;
use App\Models\Academy\Group;
use Illuminate\Support\Facades\DB;
use App\Modules\Administrator\Academy\Repositories\EnrollmentRepository;
use App\Modules\Administrator\Academy\Repositories\EnrollmentPaymentRepository;
use App\Modules\Administrator\Treasury\Repositories\Actions\CreateIncomeAction;
use App\Modules\Administrator\Inventory\Repositories\Actions\RegisterKardexMovementAction;
use App\Models\Academy\Material;

class EnrollmentService
{
    public function __construct(
        private EnrollmentRepository $enrollmentRepository,
        private EnrollmentPaymentRepository $enrollmentPaymentRepository,
        private CreateIncomeAction $createIncomeAction,
        private RegisterKardexMovementAction $registerKardexMovementAction,
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

            if (!empty($data['materials'])) {

                // NOTA: Si esto es una actualización (edit), al eliminar de EnrollmentMaterial
                // deberías hacer primero un movimiento 'in' al kardex para "devolver" el stock de los materiales anteriores
                // o calcular la diferencia en cantidades. Por simplificar, aquí solo registramos las salidas.
                EnrollmentMaterial::where('enrollment_id', $enrollment->id)->delete();

                foreach ($data['materials'] as $materialId) {
                    $enrollmentMaterial = EnrollmentMaterial::create([
                        'enrollment_id' => $enrollment->id,
                        'material_id' => $materialId,
                    ]);

                    $material = Material::with('presentation')->find($materialId);

                    if ($material && $material->presentation) {
                        // Multiplicamos la cantidad del material por las unidades que trae la presentación
                        $totalQuantityToDeduct = ($material->quantity ?? 1) * ($material->presentation->quantity ?? 1);

                        $this->registerKardexMovementAction->execute([
                            'product_id' => $material->presentation->product_id,
                            'presentation_id' => $material->presentation_id,
                            'infrastructure_id' => $infrastructureId,
                            'movement_type' => 'out',
                            'reason' => 'enrollment',
                            'quantity' => $totalQuantityToDeduct,
                            'reference_id' => $enrollmentMaterial->id,
                            'reference_type' => 'academy_enrollment_materials',
                            'notes' => 'Entrega de material por matrícula',
                        ]);
                    }
                }
            }

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
}
