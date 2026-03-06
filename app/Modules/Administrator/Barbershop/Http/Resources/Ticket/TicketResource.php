<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Ticket;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'branchId'        => $this->branch_id,
            'cashSessionId'   => $this->cash_session_id,
            'reservationId'   => $this->reservation_id,
            'profileWorkerId' => $this->profile_worker_id,
            'profileClientId' => $this->profile_client_id,
            'amount'          => (float) $this->amount,
            'discount'        => (float) $this->discount,
            'total'           => (float) $this->total,
            'ticketDate'      => $this->ticket_date?->format('Y-m-d H:i:s'),
            'status'          => $this->status,
            'services'        => $this->whenLoaded('services', fn() => $this->services->map(fn($s) => [
                'id'              => $s->id,
                'serviceBranchId' => $s->service_branch_id,
                'serviceName'     => $s->serviceBranch?->service?->name,
                'quantity'        => $s->quantity,
                'amount'          => (float) $s->amount,
                'discount'        => (float) $s->discount,
            ])),
            'sale'            => $this->whenLoaded('sale', fn() => $this->sale ? [
                'id'       => $this->sale->id,
                'subtotal' => (float) $this->sale->subtotal,
                'discount' => (float) $this->sale->discount,
                'total'    => (float) $this->sale->total,
                'status'   => $this->sale->status,
                'items'    => $this->sale->items->map(fn($item) => [
                    'id'               => $item->id,
                    'presentationId'   => $item->presentation_id,
                    'presentationName' => $item->presentation?->name,
                    'quantity'         => $item->quantity,
                    'unitPrice'        => (float) $item->unit_price,
                    'discount'         => (float) $item->discount,
                    'total'            => (float) $item->total,
                ]),
            ] : null),
        ];
    }
}
