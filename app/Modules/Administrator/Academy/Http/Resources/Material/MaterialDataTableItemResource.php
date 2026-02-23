<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Material;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'isActive' => $this->is_active,
            'product' => [
                'id' => $this->product_id,
                'name' => $this->product_name,
            ],
        ];
    }
}
