<?php

namespace App\Modules\Administrator\Academy\Repositories\Actions;

use App\Models\Academy\EnrollmentMaterial;
use App\Models\Academy\Material;
use App\Modules\Administrator\Inventory\Repositories\Actions\RegisterKardexMovementAction;

class SyncEnrollmentMaterialsAction
{
    public function __construct(
        private RegisterKardexMovementAction $registerKardexMovementAction,
    ) {}

    public function execute(int $enrollmentId, array $materialIds, int $infrastructureId): void
    {
        // ─── Verificar si los materiales cambiaron ───
        $oldEnrollmentMaterials = EnrollmentMaterial::where('enrollment_id', $enrollmentId)->get();
        $oldMaterialIds = $oldEnrollmentMaterials->pluck('material_id')->toArray();

        $materialsToAdd = array_diff($materialIds, $oldMaterialIds);
        $materialsToRemove = array_diff($oldMaterialIds, $materialIds);

        if (count($materialsToAdd) === 0 && count($materialsToRemove) === 0) {
            return;
        }

        // ─── Devolver stock de materiales anteriores (kardex 'in') ───
        foreach ($oldEnrollmentMaterials as $oldEnrollmentMaterial) {
            $material = Material::with('presentation')->find($oldEnrollmentMaterial->material_id);

            if ($material && $material->presentation) {
                $totalQuantityToReturn = ($material->quantity ?? 1) * ($material->presentation->quantity ?? 1);

                $this->registerKardexMovementAction->execute([
                    'product_id' => $material->presentation->product_id,
                    'presentation_id' => $material->presentation_id,
                    'infrastructure_id' => $infrastructureId,
                    'movement_type' => 'in',
                    'reason' => 'enrollment',
                    'quantity' => $totalQuantityToReturn,
                    'reference_id' => $oldEnrollmentMaterial->id,
                    'reference_type' => 'academy_enrollment_materials',
                    'notes' => 'Devolución de material por matrícula',
                ]);
            }
        }

        // ─── Eliminar materiales anteriores ───
        EnrollmentMaterial::where('enrollment_id', $enrollmentId)->delete();

        // ─── Registrar nuevos materiales (kardex 'out') ───
        if (!empty($materialIds)) {
            foreach ($materialIds as $materialId) {
                $enrollmentMaterial = EnrollmentMaterial::create([
                    'enrollment_id' => $enrollmentId,
                    'material_id' => $materialId,
                ]);

                $material = Material::with('presentation')->find($materialId);

                if ($material && $material->presentation) {
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
    }
}
