<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Stock;

use Illuminate\Http\Resources\Json\JsonResource;

class StockDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'currentStock' => $this->current_stock,
            'lastMovementAt' => $this->last_movement_at,
            'product' => [
                'id' => $this->product_id,
                'name' => $this->product_name,
                'sku' => $this->product_sku,
                'minStock' => $this->product_min_stock,
                'maxStock' => $this->product_max_stock,
            ],
        ];
    }
}
