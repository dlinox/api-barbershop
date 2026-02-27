<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->presentation_id,
            'title' => '[' . $this->stock_current_stock . '] ' . $this->name . ' - ' . $this->presentation_name . ' (' . $this->presentation_unit_type . ' x ' . $this->presentation_quantity . ')',
        ];
    }
}
