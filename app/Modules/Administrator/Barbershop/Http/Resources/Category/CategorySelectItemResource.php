<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategorySelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->name,
        ];
    }
}
