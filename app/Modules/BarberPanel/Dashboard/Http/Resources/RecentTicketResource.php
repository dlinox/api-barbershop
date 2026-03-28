<?php

namespace App\Modules\BarberPanel\Dashboard\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecentTicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'     => $this['id'],
            'number' => $this['number'],
            'client' => $this['client'],
            'total'  => $this['total'],
            'date'   => $this['date'],
            'status' => $this['status'],
        ];
    }
}
