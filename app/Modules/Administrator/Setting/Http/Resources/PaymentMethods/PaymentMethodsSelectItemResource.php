<?php

namespace App\Modules\Administrator\Setting\Http\Resources\PaymentMethods;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodsSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->name,
        ];
    }
}
