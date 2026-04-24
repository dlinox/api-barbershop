<?php

namespace App\Modules\BarberPanel\Pos\Http\Resources;

use App\Models\Inventory\Stock;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'cashSessionId' => $this->cash_session_id,
            'clientId'      => $this->person_id,
            'ticketId'      => $this->barbershop_ticket_id,
            'context'       => $this->context,
            'items'         => $this->items->map(function ($item) {
                $presentation = $item->presentation;
                $stock = Stock::where('presentation_id', $presentation->id)
                    ->where('infrastructure_id', $this->infrastructure_id)
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
            }),
            'income' => null,
        ];
    }
}
