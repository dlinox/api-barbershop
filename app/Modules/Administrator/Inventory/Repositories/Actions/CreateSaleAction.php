<?php

namespace App\Modules\Administrator\Inventory\Repositories\Actions;

use App\Common\Exceptions\ApiException;
use App\Models\Inventory\Sale;
use App\Models\Inventory\ProductPresentation;

class CreateSaleAction
{
    public function __construct(
        private RegisterKardexMovementAction $registerKardexMovementAction,
    ) {}

    /**
     * Crea la venta (sale + items). Si status es 'completed', descuenta stock vía kardex.
     */
    public function execute(array $data, int $infrastructureId, string $status = 'completed'): Sale
    {
        $subtotal      = 0;
        $totalDiscount = 0;

        foreach ($data['items'] as &$item) {
            $lineSubtotal   = $item['quantity'] * $item['unit_price'];
            $lineDiscount   = $item['discount'] ?? 0;
            $item['total']  = $lineSubtotal - $lineDiscount;
            $subtotal      += $lineSubtotal;
            $totalDiscount += $lineDiscount;
        }
        unset($item);

        $total = $subtotal - $totalDiscount;

        // ─── Crear cabecera de venta ───
        $sale = Sale::create([
            'infrastructure_id' => $infrastructureId,
            'cash_session_id'   => $data['cash_session_id'] ?? null,
            'person_id'         => $data['client_id'] ?? null,
            'context'           => $data['context'] ?? 'barbershop',
            'subtotal'          => $subtotal,
            'discount'          => $totalDiscount,
            'total'             => $total,
            'status'            => $status,
        ]);

        // ─── Crear ítems ───
        foreach ($data['items'] as $item) {
            $sale->items()->create([
                'presentation_id' => $item['presentation_id'],
                'quantity'        => $item['quantity'],
                'unit_price'      => $item['unit_price'],
                'discount'        => $item['discount'] ?? 0,
                'total'           => $item['total'],
            ]);
        }

        // ─── Descontar stock solo si la venta está completada ───
        if ($status === 'completed') {
            $this->registerStockMovements($sale, $data['items'], $infrastructureId);
        }

        return $sale->load('items');
    }

    /**
     * Registra las salidas en kardex para cada ítem de la venta.
     */
    public function registerStockMovements(Sale $sale, array $items, int $infrastructureId): void
    {
        foreach ($items as $item) {
            $presentation = ProductPresentation::findOrFail($item['presentation_id']);

            $this->registerKardexMovementAction->execute([
                'product_id'        => $presentation->product_id,
                'presentation_id'   => $item['presentation_id'],
                'infrastructure_id' => $infrastructureId,
                'quantity'          => $item['quantity'],
                'unit_cost'         => $item['unit_price'],
                'movement_type'     => 'out',
                'reason'            => 'sale',
                'reference_id'      => $sale->id,
                'reference_type'    => Sale::class,
                'notes'             => 'Venta POS #' . $sale->id,
            ]);
        }
    }
}
