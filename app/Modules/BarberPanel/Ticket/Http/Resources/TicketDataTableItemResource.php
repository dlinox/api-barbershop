<?php

namespace App\Modules\BarberPanel\Ticket\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'ticketNumber'      => $this->ticket_number,
            'client'            => $this->client_name
                ? trim(($this->client_paternal_surname ?? '') . ' ' . $this->client_name)
                : 'Público general',
            'branch'            => $this->branch_name,
            'amount'            => (float) $this->amount,
            'discount'          => (float) $this->discount,
            'total'             => (float) $this->total,
            'ticketDate'        => $this->ticket_date,
            'status'            => $this->status,
            'incomeId'          => $this->income_id,
            'cashSessionStatus' => $this->cash_session_status,
        ];
    }
}
