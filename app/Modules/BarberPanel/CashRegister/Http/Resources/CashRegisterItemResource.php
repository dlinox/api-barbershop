<?php

namespace App\Modules\BarberPanel\CashRegister\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashRegisterItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}
