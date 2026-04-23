<?php

namespace App\Modules\Auth\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminInfrastructureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $type = str_contains($this->infrastructurable_type, 'barbershop') ? 'barbershop' : 'academy';

        return [
            'id'   => $this->id,
            'name' => $this->infrastructurable->name,
            'type' => $type,
        ];
    }
}
