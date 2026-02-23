<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Stock;

use Illuminate\Http\Resources\Json\JsonResource;

class StockByProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'infrastructureId' => $this->infrastructure_id,
            'infrastructureName' => $this->infrastructure->name ?? null,
            'currentStock' => $this->current_stock,
            'lastMovementAt' => $this->last_movement_at,
        ];
    }
}
