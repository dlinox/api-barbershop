<?php

namespace App\Modules\Administrator\Academy\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;

use App\Modules\Administrator\Academy\Repositories\EnrollmentRepository;

class UpdateEnrollmentAction
{
    public function __construct(
        private EnrollmentRepository $enrollmentRepository,
        private SyncEnrollmentMaterialsAction $syncEnrollmentMaterialsAction,
        private GenerateEnrollmentPdfAction $generateEnrollmentPdfAction,
    ) {}

    public function execute(array $data): void
    {
        try {
            DB::beginTransaction();

            $enrollment = Enrollment::findOrFail($data['id']);

            // ─── Actualizar datos de la matrícula ───
            $this->enrollmentRepository->update([
                'id' => $data['id'],
                'group_id' => $data['group_id'],
                'date' => $data['date'],
                'status' => $data['status'],
            ]);

            // Obtener el infrastructure_id para el grupo
            $group = Group::with('branch.infrastructure')->findOrFail($data['group_id']);
            $infrastructureId = $group->branch->getInfrastructureId();

            // ─── Sincronizar materiales ───
            $this->syncEnrollmentMaterialsAction->execute(
                $enrollment->id,
                $data['materials'] ?? [],
                $infrastructureId,
            );

            DB::commit();

            // ─── Regenerar PDF de la ficha (fuera de la transacción) ───
            $this->generateEnrollmentPdfAction->execute($enrollment->id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
