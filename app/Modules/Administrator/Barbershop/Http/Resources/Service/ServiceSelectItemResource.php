<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->name,
        ];
    }
}
