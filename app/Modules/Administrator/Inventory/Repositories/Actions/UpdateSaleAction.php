<?php

namespace App\Modules\Administrator\Inventory\Repositories\Actions;

use App\Models\Inventory\Sale;

class UpdateSaleAction
{
    /**
     * Actualiza la cabecera y los ítems de una venta existente.
     */
    public function execute(array $data, Sale $sale): Sale
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

        // ─── Actualizar cabecera ───
        $sale->update([
            'cash_session_id' => $data['cash_session_id'] ?? $sale->cash_session_id,
            'person_id'       => $data['client_id'] ?? $sale->person_id,
            'barbershop_ticket_id' => $data['ticket_id'] ?? $sale->barbershop_ticket_id,
            'context'         => $data['context'] ?? $sale->context,
            'subtotal'        => $subtotal,
            'discount'        => $totalDiscount,
            'total'           => $total,
        ]);

        // ─── Reemplazar ítems ───
        $sale->items()->delete();

        foreach ($data['items'] as $item) {
            $sale->items()->create([
                'presentation_id' => $item['presentation_id'],
                'quantity'        => $item['quantity'],
                'unit_price'      => $item['unit_price'],
                'discount'        => $item['discount'] ?? 0,
                'total'           => $item['total'],
            ]);
        }

        return $sale->load('items');
    }
}
