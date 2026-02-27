<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductPresentationSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->sku . ' - ' . $this->name . ' (x' . $this->quantity . ')',
        ];
    }
}
