<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Ticket;

use Illuminate\Http\Resources\Json\JsonResource;

class WaitingQueueTicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'ticketNumber' => $this->ticket_number,
            'clientName'   => trim(($this->client_name ?? '') . ' ' . ($this->client_paternal_surname ?? '')),
            'barberName'   => $this->profile_barber_id
                ? trim(($this->barber_name ?? '') . ' ' . ($this->barber_paternal_surname ?? ''))
                : null,
            'hasBarber'    => !empty($this->profile_barber_id),
            'total'        => (float) $this->total,
            'createdAt'    => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
