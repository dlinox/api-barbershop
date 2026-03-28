<?php

namespace App\Modules\BarberPanel\Ticket\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WaitingQueueTicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'ticketNumber' => $this->ticket_number,
            'clientName'   => trim(($this->client_name ?? '') . ' ' . ($this->client_paternal_surname ?? '')),
            'total'        => (float) $this->total,
            'createdAt'    => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
