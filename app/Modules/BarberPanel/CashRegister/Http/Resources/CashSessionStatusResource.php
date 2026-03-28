<?php

namespace App\Modules\BarberPanel\CashRegister\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashSessionStatusResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'isOpen'           => $this['is_open'],
            'sessionId'        => $this['session_id'] ?? null,
            'openedBy'         => $this['opened_by'] ?? null,
            'openedAt'         => $this['opened_at'] ?? null,
            'infrastructureId' => $this['infrastructure_id'] ?? null,
        ];
    }
}
