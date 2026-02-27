<?php

namespace App\Modules\Administrator\Setting\Http\Resources\PaymentMethods;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodsDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'isActive' => $this->is_active,
            'isDefault' => $this->is_default,
        ];
    }
}
