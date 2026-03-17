<?php

namespace App\Modules\Administrator\Barbershop\Repositories\Actions;

use App\Models\Barbershop\Ticket;
use App\Models\Inventory\Sale;
use Illuminate\Support\Facades\Auth;

class CreateTicketAction
{
    /**
     * Crea o actualiza un ticket de atención (cabecera + servicios opcionales + venta pendiente opcional).
     */
    public function execute(array $data): Ticket
    {
        // ─── Crear o actualizar cabecera del ticket ───
        $ticket = Ticket::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'branch_id'         => $data['branch_id'],
                'cash_session_id'   => $data['cash_session_id'] ?? null,
                'reservation_id'    => $data['reservation_id'] ?? null,
                'profile_barber_id' => $data['barber_id'] ?? null,
                'profile_client_id' => $data['client_id'] ?? null,
                'ticket_date'       => now(),
                'status'            => 'pending',
            ]
        );

        // ─── Sincronizar servicios ───
        $this->syncServices($ticket, $data['services']);

        // ─── Crear venta pendiente si hay productos ───
        if (!empty($data['products'])) {
            $this->createPendingSale($ticket, $data);
        }

        // ─── Calcular totales (servicios + productos) ───
        $this->updateTicketTotals($ticket, $data);

        return $ticket->load('services');
    }

    /**
     * Calcula y actualiza los totales del ticket sumando servicios y productos.
     */
    private function updateTicketTotals(Ticket $ticket, array $data): void
    {
        $servicesAmount   = 0;
        $servicesDiscount = 0;
        foreach ($data['services'] as $s) {
            $qty = $s['quantity'] ?? 1;
            $servicesAmount   += $s['amount'] * $qty;
            $servicesDiscount += $s['discount'] ?? 0;
        }

        $productsAmount   = 0;
        $productsDiscount = 0;
        foreach ($data['products'] ?? [] as $p) {
            $productsAmount   += $p['unit_price'] * $p['quantity'];
            $productsDiscount += $p['discount'] ?? 0;
        }

        $totalAmount   = $servicesAmount + $productsAmount;
        $totalDiscount = $servicesDiscount + $productsDiscount;

        $ticket->update([
            'amount'   => $totalAmount,
            'discount' => $totalDiscount,
            'total'    => $totalAmount - $totalDiscount,
        ]);
    }

    /**
     * Sincroniza los servicios del ticket (elimina anteriores y crea nuevos).
     */
    public function syncServices(Ticket $ticket, array $services): void
    {
        $ticket->services()->delete();

        foreach ($services as $service) {
            $ticket->services()->create([
                'service_id' => $service['service_id'],
                'quantity'          => $service['quantity'] ?? 1,
                'amount'            => $service['amount'],
                'discount'          => $service['discount'] ?? 0,
            ]);
        }
    }

    /**
     * Crea una venta pendiente de productos asociada al ticket (sin afectar kardex/stock).
     */
    public function createPendingSale(Ticket $ticket, array $data, ?int $infrastructureId = null): Sale
    {
        // Si ya existe una venta pendiente, reemplazarla
        $existingSale = Sale::where('barbershop_ticket_id', $ticket->id)
            ->where('status', 'pending')
            ->first();

        if ($existingSale) {
            $existingSale->items()->delete();
            $existingSale->delete();
        }

        $infrastructureId = $infrastructureId ?? $ticket->branch->getInfrastructureId();

        $subtotal      = 0;
        $totalDiscount = 0;

        foreach ($data['products'] as &$item) {
            $lineSubtotal   = $item['quantity'] * $item['unit_price'];
            $lineDiscount   = $item['discount'] ?? 0;
            $item['total']  = $lineSubtotal - $lineDiscount;
            $subtotal      += $lineSubtotal;
            $totalDiscount += $lineDiscount;
        }
        unset($item);

        $total = $subtotal - $totalDiscount;

        $sale = Sale::create([
            'infrastructure_id'    => $infrastructureId,
            'barbershop_ticket_id' => $ticket->id,
            'person_id'            => $data['client_id'] ?? null,
            'context'              => 'barbershop',
            'subtotal'             => $subtotal,
            'discount'             => $totalDiscount,
            'total'                => $total,
            'status'               => 'pending',
            'user_id'              => Auth::id(),
        ]);

        foreach ($data['products'] as $item) {
            $sale->items()->create([
                'presentation_id' => $item['presentation_id'],
                'quantity'        => $item['quantity'],
                'unit_price'      => $item['unit_price'],
                'discount'        => $item['discount'] ?? 0,
                'total'           => $item['total'],
            ]);
        }

        return $sale;
    }
}
