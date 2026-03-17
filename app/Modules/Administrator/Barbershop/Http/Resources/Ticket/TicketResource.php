<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Ticket;

use App\Models\Inventory\Stock;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'branchId'      => $this->branch_id,
            'cashSessionId' => $this->cash_session_id,
            'reservationId' => $this->reservation_id,
            'barberId'      => $this->profile_barber_id,
            'clientId'      => $this->profile_client_id,
            'amount'        => (float) $this->amount,
            'discount'      => (float) $this->discount,
            'total'         => (float) $this->total,
            'ticketDate'    => $this->ticket_date?->format('Y-m-d H:i:s'),
            'status'        => $this->status,
            'services'      => $this->whenLoaded('services', fn() => $this->services->map(fn($s) => [
                'serviceId' => $s->service_id,
                'name'            => $s->service?->name,
                'category'        => $s->service?->category?->name ?? '',
                'quantity'        => $s->quantity,
                'amount'          => (float) $s->amount,
                'discount'        => (float) $s->discount,
                'duration'        => $s->service?->duration,
            ])),
            'products'      => $this->sale ? $this->sale->items->map(function ($item) {
                $presentation = $item->presentation;
                $stock = Stock::where('presentation_id', $presentation->id)
                    ->where('infrastructure_id', $this->sale->infrastructure_id)
                    ->first();

                return [
                    'presentationId'       => $item->presentation_id,
                    'quantity'             => (int) $item->quantity,
                    'unitPrice'            => (float) $item->unit_price,
                    'discount'             => (float) $item->discount,
                    'presentation'         => $presentation->name,
                    'presentationQuantity' => (int) $presentation->quantity,
                    'product'              => $presentation->product?->name,
                    'stock'                => (int) ($stock?->current_stock ?? 0),
                ];
            }) : [],
            'income'        => null,
        ];
    }
}
