<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'isActive' => (bool) $this->is_active,
        ];
    }
}
