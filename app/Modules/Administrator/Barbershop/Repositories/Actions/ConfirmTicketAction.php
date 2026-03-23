<?php

namespace App\Modules\Administrator\Barbershop\Repositories\Actions;

use App\Common\Exceptions\ApiException;
use App\Models\Barbershop\Ticket;
use App\Models\Inventory\ProductPresentation;
use App\Models\Inventory\Sale;
use App\Modules\Administrator\Inventory\Repositories\Actions\RegisterKardexMovementAction;
use App\Modules\Administrator\Treasury\Repositories\Actions\CreateIncomeAction;

class ConfirmTicketAction
{
    public function __construct(
        private RegisterKardexMovementAction $registerKardexMovementAction,
        private CreateIncomeAction $createIncomeAction,
    ) {}

    /**
     * Confirma un ticket ya creado: completa venta pendiente (kardex/stock), crea ingreso.
     */
    public function execute(Ticket $ticket, array $data, int $infrastructureId): array
    {
        if ($ticket->status === 'confirmed') {
            throw new ApiException('El ticket ya fue confirmado.');
        }

        // ─── Completar venta pendiente (registrar kardex y actualizar stock) ───
        $sale = $ticket->sale;

        if ($sale && $sale->status === 'pending') {
            $this->completePendingSale($sale, $infrastructureId, $data['cash_session_id']);
        }

        // ─── Actualizar montos y estado del ticket (servicios + productos) ───
        $servicesAmount   = $ticket->services->sum(fn($s) => $s->amount * $s->quantity);
        $servicesDiscount = $ticket->services->sum('discount');

        $productsAmount   = 0;
        $productsDiscount = 0;
        if ($sale) {
            $productsAmount   = $sale->items->sum(fn($i) => $i->unit_price * $i->quantity);
            $productsDiscount = $sale->items->sum('discount');
        }

        $totalAmount   = $servicesAmount + $productsAmount;
        $totalDiscount = $servicesDiscount + $productsDiscount;

        $ticket->update([
            'cash_session_id' => $data['cash_session_id'],
            'amount'          => $totalAmount,
            'discount'        => $totalDiscount,
            'total'           => $totalAmount - $totalDiscount,
            'status'          => 'confirmed',
        ]);

        // ─── Crear ingreso (income) ───
        $incomeData                    = $data['income'];
        $incomeData['cash_session_id'] = $data['cash_session_id'];
        $incomeData['client_id']       = $data['client_id'] ?? $ticket->profile_client_id;

        $income = $this->createIncomeAction->execute(
            data: $incomeData,
            infrastructureId: $infrastructureId,
            transactionableType: 'barbershop_tickets',
            transactionableId: $ticket->id,
        );

        return [$ticket, $income];
    }

    /**
     * Completa una venta pendiente: registra movimientos de kardex, actualiza stock y cambia estado.
     */
    private function completePendingSale(Sale $sale, int $infrastructureId, int $cashSessionId): void
    {
        foreach ($sale->items as $item) {
            $presentation = ProductPresentation::findOrFail($item->presentation_id);

            $this->registerKardexMovementAction->execute([
                'product_id'        => $presentation->product_id,
                'presentation_id'   => $item->presentation_id,
                'infrastructure_id' => $infrastructureId,
                'quantity'          => $item->quantity,
                'unit_cost'         => $item->unit_price,
                'movement_type'     => 'out',
                'reason'            => 'sale',
                'reference_id'      => $sale->id,
                'reference_type'    => Sale::class,
                'notes'             => 'Venta POS #' . $sale->id,
            ]);
        }

        $sale->update([
            'status'          => 'completed',
            'cash_session_id' => $cashSessionId,
        ]);
    }
}
