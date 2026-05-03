<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'categoryId' => $this->category_id,
            'categoryName' => $this->category_name,
            'branchName' => $this->branch_name,
            'price' => $this->price,
            'duration' => $this->duration,
            'isActive' => (bool) $this->is_active,
        ];
    }
}
