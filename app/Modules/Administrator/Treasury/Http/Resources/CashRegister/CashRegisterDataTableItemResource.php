<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\CashRegister;

use Illuminate\Http\Resources\Json\JsonResource;

class CashRegisterDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'infrastructureId' => $this->infrastructure_id,
            'name'             => $this->name,
            'isActive'         => $this->is_active,
        ];
    }
}
