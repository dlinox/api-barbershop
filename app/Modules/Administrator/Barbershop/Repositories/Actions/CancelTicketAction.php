<?php

namespace App\Modules\Administrator\Barbershop\Repositories\Actions;

use App\Common\Exceptions\ApiException;
use App\Models\Barbershop\Ticket;
use App\Models\Inventory\Sale;
use App\Modules\Administrator\Inventory\Repositories\Actions\RegisterKardexMovementAction;

class CancelTicketAction
{
    public function __construct(
        private RegisterKardexMovementAction $registerKardexMovementAction,
    ) {}

    /**
     * Cancela un ticket.
     * - Pendiente: cancela ticket y venta pendiente (sin revertir stock).
     * - Confirmado: cancela ticket, revierte stock/kardex si hubo venta con productos.
     */
    public function execute(int $ticketId): Ticket
    {
        $ticket = Ticket::with(['sale.items.presentation', 'cashSession'])->findOrFail($ticketId);

        if ($ticket->status === 'cancelled') {
            throw new ApiException('El ticket ya fue cancelado.');
        }

        if ($ticket->cashSession && $ticket->cashSession->status === 'closed') {
            throw new ApiException('No se puede cancelar un ticket de una sesión de caja cerrada.');
        }

        $branch           = $ticket->branch;
        $infrastructureId = $branch->getInfrastructureId();
        $sale             = $ticket->sale;

        if ($ticket->status === 'confirmed' && $sale && $sale->status === 'completed') {
            // ─── Revertir stock (devolución) ───
            foreach ($sale->items as $item) {
                $this->registerKardexMovementAction->execute([
                    'product_id'        => $item->presentation->product_id,
                    'presentation_id'   => $item->presentation_id,
                    'infrastructure_id' => $infrastructureId,
                    'quantity'          => $item->quantity,
                    'unit_cost'         => $item->unit_price,
                    'movement_type'     => 'in',
                    'reason'            => 'return',
                    'reference_id'      => $sale->id,
                    'reference_type'    => Sale::class,
                    'notes'             => 'Devolución por cancelación de ticket #' . $ticket->id,
                ]);
            }

            $sale->update(['status' => 'cancelled']);
        }

        // Cancelar venta pendiente si existe
        if ($sale && $sale->status === 'pending') {
            $sale->update(['status' => 'cancelled']);
        }

        $ticket->update(['status' => 'cancelled']);

        return $ticket->load(['services', 'sale.items']);
    }
}
