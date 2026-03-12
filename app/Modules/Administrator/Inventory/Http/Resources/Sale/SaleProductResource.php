<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Sale;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'product' => [
                'id' => $this->product_id,
                'name' => $this->product_name,
            ],
            'presentation' => [
                'id' => $this->id,
                'name' => $this->name,
                'sku' => $this->sku,
                'quantity' => $this->quantity,
                'salePrice' => (float) $this->sale_price,
            ],
            'stock' => (float) ($this->current_stock ?? 0),
        ];
    }
}
