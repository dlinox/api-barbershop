<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\CashRegister;

use Illuminate\Http\Resources\Json\JsonResource;

class CashRegisterSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->name,
        ];
    }
}
