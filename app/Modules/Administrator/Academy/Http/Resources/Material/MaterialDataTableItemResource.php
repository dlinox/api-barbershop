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
            'branch' => [
                'id' => $this->branch_id,
                'name' => $this->branch_name,
            ],
            'product' => [
                'id' => $this->product_id,
                'name' => $this->product_name,
                'presentation' => [
                    'id' => $this->product_presentation_id,
                    'name' => $this->product_presentation_name,
                    'unitType' => $this->product_presentation_unit_type,
                    'quantity' => $this->product_presentation_quantity,
                ],
            ],
        ];
    }
}
