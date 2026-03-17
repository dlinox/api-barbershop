<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceSelectItemByInfrastructureResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'serviceId' => $this->id,
            'name' => $this->name,
            'category' => $this->category_name,
            'amount' => $this->price,
            'duration' => $this->duration,
            'quantity' => 1,
            'discount' => 0,
        ];
    }
}
