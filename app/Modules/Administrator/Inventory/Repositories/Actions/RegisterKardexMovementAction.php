<?php

namespace App\Modules\Administrator\Inventory\Repositories\Actions;

use App\Models\Inventory\Kardex;
use App\Models\Inventory\Stock;
use App\Modules\Administrator\Inventory\Repositories\KardexRepository;

class RegisterKardexMovementAction
{
    public function __construct(
        private KardexRepository $kardexRepository
    ) {}

    public function execute(array $data): Kardex
    {
        $lastBalance = $this->kardexRepository->getLastBalance($data['presentation_id'], $data['infrastructure_id']);

        $prevQuantity  = $lastBalance?->balance_quantity ?? 0;
        $prevUnitCost  = (float) ($lastBalance?->balance_unit_cost ?? 0);
        $prevTotalCost = (float) ($lastBalance?->balance_total_cost ?? 0);

        $quantity = $data['quantity'];
        $unitCost = (float) ($data['unit_cost'] ?? $prevUnitCost);

        // ─── Calcular saldos según tipo de movimiento ───
        switch ($data['movement_type']) {
            case 'in':
                $balanceQuantity  = $prevQuantity + $quantity;
                $balanceTotalCost = $prevTotalCost + ($quantity * $unitCost);
                $balanceUnitCost  = $balanceQuantity > 0 ? round($balanceTotalCost / $balanceQuantity, 2) : 0;
                break;

            case 'out':
                $balanceQuantity  = $prevQuantity - abs($quantity);
                $balanceTotalCost = $prevTotalCost - (abs($quantity) * $prevUnitCost);
                $balanceUnitCost  = $prevUnitCost;
                $quantity         = -abs($quantity);
                break;

            case 'adjustment':
                $balanceQuantity  = $quantity;                              // reemplazo total
                $quantity         = $balanceQuantity - $prevQuantity;       // diferencia para el kardex
                $balanceTotalCost = $balanceQuantity > 0 ? $balanceQuantity * $unitCost : 0;
                $balanceUnitCost  = $unitCost;
                break;

            default:
                $balanceQuantity  = $prevQuantity;
                $balanceUnitCost  = $prevUnitCost;
                $balanceTotalCost = $prevTotalCost;
        }

        $totalCost = abs($quantity) * $unitCost;

        // ─── Registrar movimiento en kardex ───
        $kardex = $this->kardexRepository->create([
            'product_id'         => $data['product_id'],
            'presentation_id'    => $data['presentation_id'],
            'infrastructure_id'  => $data['infrastructure_id'],
            'movement_type'      => $data['movement_type'],
            'reason'             => $data['reason'],
            'quantity'           => $quantity,
            'unit_cost'          => $unitCost,
            'total_cost'         => $totalCost,
            'balance_quantity'   => $balanceQuantity,
            'balance_unit_cost'  => $balanceUnitCost,
            'balance_total_cost' => $balanceTotalCost,
            'reference_id'       => $data['reference_id'] ?? null,
            'reference_type'     => $data['reference_type'] ?? null,
            'notes'              => $data['notes'] ?? null,
        ]);

        // ─── Actualizar stock ───
        Stock::updateOrCreate(
            [
                'product_id'        => $data['product_id'],
                'presentation_id'   => $data['presentation_id'],
                'infrastructure_id' => $data['infrastructure_id'],
            ],
            [
                'current_stock'    => $balanceQuantity,
                'last_movement_at' => now(),
            ]
        );

        return $kardex;
    }
}
