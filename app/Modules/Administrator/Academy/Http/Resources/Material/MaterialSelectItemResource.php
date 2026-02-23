<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Material;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->product_name . ' (Cantidad: ' . $this->quantity . ')',
        ];
    }
}
