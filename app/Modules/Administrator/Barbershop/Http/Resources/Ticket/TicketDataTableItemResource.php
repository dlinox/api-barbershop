<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Ticket;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'branchName' => $this->branch_name,
            // 'workerName' => trim(($this->worker_name ?? '') . ' ' . ($this->worker_paternal_surname ?? '')),
            'clientName' => trim(($this->client_name ?? '') . ' ' . ($this->client_paternal_surname ?? '') . ' ' . ($this->client_maternal_surname ?? '')),
            'amount'     => (float) $this->amount,
            'discount'   => (float) $this->discount,
            'total'      => (float) $this->total,
            'ticketDate' => $this->ticket_date,
            'status'     => $this->status,
            'incomeId'   => $this->income_id,
            'createdAt'  => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
